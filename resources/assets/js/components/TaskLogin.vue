<template>
  
        <div class="row">
            
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>

                    <div class="panel-body">
                        <form class="form-horizontal"  v-on:submit="iniciar()">
                            

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" v-model="datos.email" class="form-control" name="email"  required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" v-model="datos.password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button class="btn btn-primary">
                                        Login
                                    </button>
                                    <router-link :to="{name: 'register'}" class="btn btn-warning">Register</router-link>
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
            email: "",
            password: ""
        }
        
    };
  },
  methods: {
    iniciar(){
         event.preventDefault()
         var app = this;
         var login = app.datos;
         axios
        .post("/api/login", login)
        .then(function(resp) {
            
            console.log(resp.data.success.token);
            app.$router.push({name: 'index', params: {token: resp.data.success.token}});
           

        })
        .catch(function(resp) {
          console.log(resp.response.data);
          alert("Could not login");
        });
        
      }
  }
} 
</script>
<style scoped>
a.btn.btn-warning {
    float: right;
}
</style>
