
<template>
        <div class="modal fade" tabindex="-1" role="dialog" id="create_model"><!--  ojo0000000000  -->
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Create new Task</h4> <!--  ojo0000000000  -->
                    </div>
                    <div class="modal-body">
 
                        
                       <div class="form-group">
                            

                            <div class="panel panel-default">
                               
                                <div class="panel-body">
                                <form v-on:submit="guardar()">
            
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Task name</label>
                                                <input type="text" v-model="tarea.nombre_tarea" class="form-control" required autofocus>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Date start</label>
                                                <input type="date" v-model="tarea.fecha_inicio" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Date finish</label>
                                                <input type="date" v-model="tarea.fecha_finalizacion" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                                <div class="col-xs-12 form-group">
                                                    
                                                        <label class="control-label">Select file</label>
                                                    
                                                    <div class="col-md-8">
                                                        <input type="file" accept ="image/x-png, image/gif, image/jpeg, application/pdf"  v-on:change="cargarArchivo" class="form-control">
                                                    </div>
                                                </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <button class="btn btn-success">Create</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                                        </div>
                    <div class="modal-footer">
                    
                        <button type="button" class="btn btn-default" @click="hideCreate($event)">Close</button><!--  ojo0000000000  -->
                        
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</template>

<script>
export default {
  props: {
    modal: {
      type: Boolean,
      required: false,
      default: false
    }
    //authenticatedUser

  },
  data: function() {
    return {
      visualizar: false,
      tarea: {
        nombre_tarea: "",
        fecha_inicio: "",
        fecha_finalizacion: ""
      },
      archivo: ""
     
    };
  },
  methods: {
    validarFechas(){
       
        var fecha_i = this.tarea.fecha_inicio.split("-");
        var fecha_f = this.tarea.fecha_finalizacion.split("-");
        var inicio = new Date(parseInt(fecha_i[0]),parseInt(fecha_i[1]),parseInt(fecha_i[2]));
        var fin = new Date(parseInt(fecha_f[0]),parseInt(fecha_f[1]),parseInt(fecha_f[2]));
      
        if( inicio  <  fin  ){  
            
            return true;
        }else{
           
            return false;
        }
    },
    hideCreate(evnt){
        evnt.preventDefault()
        this.$emit('hideCreate')
      },
    guardar() {
      event.preventDefault();
      var app = this;

      if(app.validarFechas()){  

        var formData = new FormData();
        formData.append('nombre_tarea', app.tarea.nombre_tarea);
        formData.append('fecha_inicio', app.tarea.fecha_inicio);
        formData.append('fecha_finalizacion', app.tarea.fecha_finalizacion);
        formData.append('archivo', app.archivo);

            
        
        axios
            .post("/api/v1/tasks", formData)
            .then(function(resp) {
                
                app.tarea = {
                                nombre_tarea: "",
                                fecha_inicio: "",
                                fecha_finalizacion: ""
                            };
                alert("Task created");

            })
            .catch(function(resp) {
            console.log(resp.response.data);
            alert("Could not create your task");
            });

        }else{

            alert("Check the dates");

        }
    },
    cargarArchivo(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
                this.archivo = (files[0]);
        }
  },
   watch: {
            visualizar: function(val) {
            var app = this;

                if (app.visualizar == true) {
                    $("#create_model").modal("show");
                } else {
                    $("#create_model").modal("hide");
                }
            },
            
            modal: function(val){
        
                this.visualizar = val; 
                
            }
           
        }      
};
</script>
