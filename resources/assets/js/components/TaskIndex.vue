
<template>
    <div>
      
       
         <div class="col-md-10 floater">
            
           <div class="nvt-input-group third">
             
              <input type="text" id="viewSearch" class="form-control" placeholder="Search by name" v-model="filtro">
            </div>
              
           <div class="nvt-button-group two-third right-align">
            
              <button type="button" class="btn  btn-success" v-on:click="mostarCrear" >Create new task</button>
              
            </div>
         </div>

         


        <div class="panel panel-default">
            <div class="panel-heading">Tasks list 
            
            <select class="selectpicker" data-style="btn-info right-align" v-model.number="num_registros">
              <option>10</option>
              <option>25</option>
              <option>50</option>
            </select></div>
            <div class="panel-body" v-show="tareas.length > 0">
               
                <table class="table table-bordered table-striped">
                    <thead>
                        
                    <tr>
                        <th><a href="#" @click="ordenar($event,'nombre_tarea')"> Name</a></th>
                        <th>Date Start</th>
                        <th>Date Finish</th>
                        <th><a href="#" @click="ordenar($event,'estado')"> Status</a></th>
                        <th width="100">&nbsp;</th>
                    </tr>

                    </thead>
                    <tbody>
                       <tr v-for="task, index in filtrar" > 
                        <td>{{ task.nombre_tarea }}</td>
                        <td>{{ task.fecha_inicio }}</td>
                        <td>{{ task.fecha_finalizacion }}</td>
                        <td v-if="task.estado === 'Finalizada'" bgcolor="#FF0000">{{ task.estado }}</td>
                        <td v-else-if="task.estado === 'Iniciada'" bgcolor="#00FF00">{{ task.estado }}</td>
                        <td v-else >{{ task.estado }}</td>
                        <td>
                            
                            <button type="button" class="btn btn-info btn-xs" v-on:click="mostarVer(task)" ><span class="glyphicon glyphicon-eye-open"></span></button>
                            <button type="button" class="btn btn-warning btn-xs" v-on:click="mostarEditar(task)" ><span class="glyphicon glyphicon-pencil"></span></button>
                            <a href="#" class="btn btn-danger btn-xs"
                               
                               v-on:click="eliminar(task.id, index)"><span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center"> 
                   
                    <paginar :source="paginacion"  @navigate="navegacion"> </paginar>
                </div>

            </div>
            <div class="panel-body" v-if="tareas.length < 1">
                <h3> Cargando datos... </h3>
            </div>

            <crear-tarea :modal="estado_modal_crear"  @hideCreate="ocultarCrear"> </crear-tarea>
            <editar-tarea :modaleditar="estado_modal_editar"  :tareaeditar="tarea" :edicion="editar_tarea" @hideEdit="ocultarEditar"> </editar-tarea>

        </div>
    </div>
</template>

<script>
 import TaskPagination from './TaskPagination.vue';
 import TaskCreate from './TaskCreate.vue';
 import TaskEdit from './TaskEdit.vue';

export default {
  props: {
    pagina: {
      type: Number,
      required: false,
      default: 1
    },
    token: {type : String, 
            required: true,
    } 
   
  },
  components: {
      'paginar': TaskPagination ,
      'crear-tarea': TaskCreate ,
      'editar-tarea': TaskEdit ,
  },
  data: function() {
    return {
      estado_modal_crear: false,
      estado_modal_editar: false,
      estado_modal_archivo: false,
      editar_tarea: true,
      tarea: [],
      num_registros: 10,
      sentido_orden: -1,
      filtro: "",
      tareas: [],
      paginacion: [],
      //isAuth: null
    };
  },
  /*created() {
        this.isAuth = this.$auth.isAuthenticated();
        this.setAuthenticatedUser();
    },*/
  mounted() {
    var app = this;

    axios.defaults.headers.common = {
    "Authorization": "Bearer "+app.token,
    "Accept": "application/json",
    }

    axios
      .get(
        "/api/v1/tasks/pagination/" +
          app.num_registros +
          "?page=" +
          app.pagina
      )
      .then(function(resp) {
        app.tareas = resp.data.data;
        app.paginacion = resp.data;
      })
      .catch(function(resp) {
        console.log(resp.response.data);
        alert("Could not load tasks");
      });
  },

  
  methods: {
    setAuthenticatedUser() {
            this.$http.get('api/user')
                    .then(response => {
                        this.$auth.setAuthenticatedUser(response.body);
                        console.log(this.$auth.getAuthenticatedUser());
                    })
        },
    navegacion(pag) {
      var app = this;
      axios
        .get(
          "/api/v1/tasks/pagination/" +
            app.num_registros +
            "?page=" +
            pag
        )
        .then(function(resp) {
          app.tareas = resp.data.data;
          app.paginacion = resp.data;
        })
        .catch(function(resp) {
          console.log(resp.response.data);
          alert("Could not load task");
        });
    },
    mostarCrear(){
      this.estado_modal_crear=true;
    },
    ocultarCrear(){
     
     this.estado_modal_crear=false;
     this.cargarRegistros();

    },
     

    mostarEditar(tar){
      this.tarea = tar;  
      this.estado_modal_editar=true;
      this.editar_tarea=true;
    },
    ocultarEditar(){
        
     this.estado_modal_editar=false;
     this.ver_tarea=false;
     this.cargarRegistros();

    },
    mostarVer(tar){
      this.tarea = tar;  
      this.estado_modal_editar=true;
      this.editar_tarea=false;
    }, 

    cargarRegistros(){
        var app = this;
        axios
      .get(
        "/api/v1/tasks/pagination/" +
          app.num_registros +
          "?page=" +
          app.paginacion.current_page
      )
      .then(function(resp) {
        app.tareas = resp.data.data;
        app.paginacion = resp.data;
      })
      .catch(function(resp) {
        console.log(resp.response.data);
        alert("Could not load tasks");
      });
    },

    ordenar(evnt, property) {
      evnt.preventDefault();
      if (this.sentido_orden == 1) {
        this.sentido_orden = -1;
        this.tareas = _.orderBy(this.tareas, property, "desc");
      } else {
        this.sentido_orden = 1;
        this.tareas = _.orderBy(this.tareas, property, "asc");
      }
    },
   
    eliminar(id, index) {
      if (confirm("Do you really want to delete it?")) {
        var app = this;
        axios
          .delete("/api/v1/tasks/" + id)
          .then(function(resp) {
            app.tareas.splice(index, 1);
          })
          .catch(function(resp) {
            console.log(resp.response.data);
            alert("Could not delete task");
          });
      }
    }
  },
  computed: {
    filtrar() {
      return this.tareas.filter(tarea => {
        return tarea.nombre_tarea.includes(this.filtro);
      });
    },
    authenticatedUser() {
        return this.$auth.getAuthenticatedUser()
    }
  },
  watch: {
    num_registros: function(val) {
      var app = this;
      axios
        .get("/api/v1/tasks/pagination/" + val)
        .then(function(resp) {
          app.tareas = resp.data.data;
          app.paginacion = resp.data;
        })
        .catch(function(resp) {
          console.log(resp.response.data);
          alert("Could not load tasks");
        });
    }
  }
};
</script>


<style scoped>

select.selectpicker {
    float: right;
}
.panel.panel-default {
    margin-top: 10%;
}
.panel.panel-default {
    margin-top: 10%;
}
.nvt-input-group.third {
    width: 25%;
    float: left;
}
.nvt-button-group.two-third.right-align {
    float: right;
}
.col-md-10.floater {
    width: 100%;
    float: inherit;
}

</style>
