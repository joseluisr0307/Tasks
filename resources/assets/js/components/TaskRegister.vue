<template>
  
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" v-on:submit="registrar()">
                       <div class="form-group">
                           <label class="col-md-4 control-label">Photo</label>
                            <div class="col-md-12">                                
                                <div class="col-md-2">
                                    <img :src="image" class="img-responsive">
                                </div>
                                <div class="col-md-8">
                                    <input type="file" accept ="image/x-png, image/gif, image/jpeg"  v-on:change="onFileChange" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" v-model="datos.name" class="form-control" name="name"  required autofocus>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" v-model="datos.last_name" class="form-control" name="last_name" required autofocus>
                       
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="type_document" class="col-md-4 control-label">Type Document</label>

                            <div class="col-md-6">
                                
                                <select v-model.number="datos.type_document" class="form-control" required>
                                    <option v-for="documento in documentos" v-bind:value="documento.id">
                                        {{ documento.name }}
                                    </option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="number_document" class="col-md-4 control-label">Number Document</label>

                            <div class="col-md-6">
                                <input id="number_document" v-model="datos.number_document" type="text" class="form-control" name="number_document"  required autofocus>
                  
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" v-model="datos.email" class="form-control" name="email"  required>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" v-model="datos.password" class="form-control" name="password" required>
                     
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" v-model="datos.password_confirm" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>


<script>
export default {
   data: function() {
    return {
        datos : {
            name : "",
            last_name: "",
            type_document: "", 
            number_document: "",
            email: "",
            password: "",
            password_confirm: ""
        },
        documentos: [],
        image: ''
        
    };
  },
  methods: {
    registrar(){
         event.preventDefault()
         var app = this;
        
        var formData = new FormData();
        formData.append('name', app.datos.name);
        formData.append('last_name', app.datos.last_name);
        formData.append('type_document', app.datos.type_document);
        formData.append('number_document', app.datos.number_document);
        formData.append('email', app.datos.email);
        formData.append('password', app.datos.password);
        formData.append('password_confirm', app.datos.password_confirm);
        formData.append('photo', app.image);


         axios
        .post("/api/register", formData)
        .then(function(resp) {
            
            console.log(resp.data.success.token);
            app.$router.push({name: 'index', params: {token: resp.data.success.token}});
           

        })
        .catch(function(resp) {
          console.log(resp.response.data);
          alert("Could not register");
        });
        
      },
       onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
                    
            };
            reader.readAsDataURL(file);
   
                
                
        },
  },
  mounted() {
    var app = this;
    axios
      .get(
        "/api/documentos/"         
      )
      .then(function(resp) {
          console.log(resp.data);
        app.documentos = resp.data;
      })
      .catch(function(resp) {
        console.log(resp.response.data);
       
      });


    },
} 
</script>