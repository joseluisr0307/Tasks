<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit_model"><!--  ojo0000000000  -->
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4  v-if="edicion" class="modal-title">Edit Task</h4> <!--  ojo0000000000  -->
                        <h4  v-if="edicion === false" class="modal-title">View Task</h4>
                    </div>
                    <div class="modal-body">
 
                        
                       <div class="form-group">


                            <div class="panel panel-default">
                            
                                <div class="panel-body">
                                    <form v-on:submit="editar()">
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Task name: </label>
                                                <input type="text" v-model="tarea_editar.nombre_tarea"  class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Date start</label>
                                                <input type="date" v-model="tarea_editar.fecha_inicio"  class="form-control"  required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Date finish</label>
                                                <input type="date" v-model="tarea_editar.fecha_finalizacion" value="tarea.fecha_finalizacion" class="form-control"  required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <label class="control-label">Status</label>
                                                <input type="text" v-model="tarea_editar.estado"   class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="row" v-if="edicion">
                                            <div class="col-xs-12 form-group">
                                                <button class="btn btn-success">Edit Info</button>
                                            </div>
                                        </div>
                                    </form>

                                    <hr/>

                                    <div class="form-group" v-if="edicion">
                            
                                            
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-8">
                                                        <input type="file" v-on:change="cargarArchivo" accept ="image/x-png, image/gif, image/jpeg, application/pdf" class="form-control">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <br>
                                                       <button type="button" class="btn btn-success" @click="subirArchivo">Upload new file</button>
                                                    </div>
                                                </div>
                                            </div>


                                    </div>

                                    <div class="form-group">
                                        <div class="grid">
                                            <div class="imagetasks-data" v-for="archivo, index in archivos">
                                                <img v-if="archivo.tipo != 'pdf'" align = "left" :src=archivo.ruta :alt=archivo.nombre_archivo>    
                                                <section  v-if="archivo.tipo != 'pdf' && edicion">
                                                    <a href="#" v-on:click="eliminarArchivo(archivo.id, index)">Delete</a>
                                                </section>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <table class="table">
                                       
                                            <thead>			
                                                <th>PDF</th>				
                                                <th>Acciones</th>
                                            </thead>
                                                                                      
                                            <tbody>
                                                <tr v-for="archivo, index in archivos" > 
                                                    <td v-if="archivo.tipo === 'pdf'">{{ archivo.nombre_archivo }}</td>
                                                    <td v-if="archivo.tipo === 'pdf'">
                                                        <a :href=archivo.ruta class="btn btn-primary btn-social" target="_blank"><i class="fa fa-file-pdf-o"></i><img class="pdf" :src="icono"></a>
                                                        <a  v-if="edicion" href="#" class="btn btn-danger " v-on:click="eliminarArchivo(archivo.id, index)"><span class="glyphicon glyphicon-trash"></span></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                       </div>
                    
                        <div class="modal-footer">
                    
                            <button type="button" class="btn btn-default" @click="hideEdit($event)">Close</button><!--  ojo0000000000  -->
                        
                        </div>
                     
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

</template>

<script>
    export default {
         props: {
            modaleditar: {
                type: Boolean,
                required: false,
                default: false
            },
            tareaeditar: {
                type: [Object, Array]
                
            },
            edicion: {
                type: Boolean,
                required: false,
                default: true
                
            }
            
        },
        
        data: function () {
            return {
                visualizar_editar: false,
                
                tarea_editar: {
                    nombre_tarea: "",
                    fecha_inicio: "",
                    fecha_finalizacion: "",
                    estado: ""
                },
                archivos: [],
                archivo: [],
                imagen:"",
                icono: "/images/pdf.png"
            }
        },
       
        methods: {
            validarFechas(){
       
                var fecha_i = this.tarea_editar.fecha_inicio.split("-");
                var fecha_f = this.tarea_editar.fecha_finalizacion.split("-");
                var inicio = new Date(parseInt(fecha_i[0]),parseInt(fecha_i[1]),parseInt(fecha_i[2]));
                var fin = new Date(parseInt(fecha_f[0]),parseInt(fecha_f[1]),parseInt(fecha_f[2]));
            
                if( inicio  <  fin  ){  
                    
                    return true;
                }else{
                
                    return false;
                }
            },
            hideEdit(evnt){
                evnt.preventDefault()
                this.$emit('hideEdit')
            },
            editar() {
                event.preventDefault();
                var app = this;

                if(app.validarFechas()){  

                    axios.patch('/api/v1/tasks/' + app.tarea_editar.id, {'id': app.tarea_editar.id, 'fecha_inicio': app.tarea_editar.fecha_inicio, 'fecha_finalizacion': app.tarea_editar.fecha_finalizacion})
                        .then(function (resp) {
                            app.$emit('hideEdit')
                        })
                        .catch(function (resp) {
                            console.log(resp.response.data);
                            alert("Could not edit your task");
                        });
                }else{

                alert("Check the dates");

                }
                },
                eliminarArchivo(id, index){
                    
                    if (confirm("Do you really want to delete it?")) {
                        var app = this;
                        axios
                        .delete("/api/v1/tasks/deletefiles/" + id)
                        .then(function(resp) {
                            app.archivos.splice(index, 1);
                        })
                        .catch(function(resp) {
                            console.log(resp.response.data);
                            alert("Could not delete it");
                        });
                    }
                },
                cargarArchivo(e) {
                    var app = this;
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                            app.archivo = (files[0]);
                },subirArchivo(){
                    var app = this;
                    let formData = new FormData();

                    formData.append('id', app.tarea_editar.id);
                    formData.append('archivo', app.archivo);

                    axios.post('/api/v1/tasks/uploadfiles',formData).then(response => {
                            alert("File upload sucessful");
                            app.archivos.push(response.data);
                    }).catch((resp) => {
                            console.log(resp);
                            alert("Could not upload your file");
                        });
                }
        },
         watch: {
            visualizar_editar: function(val) {
            var app = this;

                if (app.visualizar_editar == true) {
                    $("#edit_model").modal("show");
                } else {
                    $("#edit_model").modal("hide");
                }
            },
            
            modaleditar: function(val){
        
                this.visualizar_editar = val; 
                
            },

            tareaeditar: function(val){      
               
               this.tarea_editar = val;
                
            },tarea_editar: function(){

                var app = this;

                let instance = axios.create({
                        headers: {
                            
                        }
                });

               instance.get(
                    "/api/archivos/" + app.tarea_editar.id
                    
                )
                .then(function(resp) {
                   app.archivos = resp.data;
                })
                .catch(function(resp) {
                    console.log(resp.response.data);
                    alert("Could not load files");
                });
            } 
           
        }      
    }
</script>


<style scoped>
.grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}

.imagetasks {
  display: flex;
  justify-content: space-around;
  align-items: center;
  margin: 1em;
  background-color: #64B5F6;
  width: 25%;
  height: 130px;
  border-radius: 5px;
  box-shadow: 2px -2px 20px 3px rgba(0,0,0,0.75);
}

 &-data {
    display: flex;
    flex-direction: column;
  }

  img {
    width: 150px;
}

img.pdf {
    width: 20px;
}

</style>
