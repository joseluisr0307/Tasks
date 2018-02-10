<template>
  <nav aria-label="...">
    <ul class="pagination">
  
       <li :class="{disabled: source.current_page == 1}">
        <a href="#" @click="navigateNextPrev($event,source.current_page-1)"  aria-label="Previous">  
          <span aria-hidden="true">&laquo;</span>  
        </a>
      </li>
      
      <li v-for="page in pages"  :class="{ active: source.current_page == page}">
        <a href="#" @click="navigate($event,page)">  {{page}}</a>
      </li>

      <li :class="{disabled: source.current_page == source.last_page}">
        <a href="#" @click="navigateNextPrev($event,source.current_page+1)"  aria-label="Next">  
          <span aria-hidden="true">&raquo;</span>  
        </a>
      </li>


    </ul>
  </nav>
</template>

<script>


/*
npm install -- save lodash
https://lodash.com
*/
import {range} from 'lodash'

export default {
  /*
  Propiedades del componente.
  recordar que las propiedades pueden ser accesibles pero nunca deben ser modificado sus valores!
  */
    //props : ['source'], argumentos sin validaciones
    props : {
      source : {type : [Object,Array], 
                required: true,} 
      },
  /* 
  Datos del componente.
  los datos pueden ser accesibles y modificables 
  */
    data() {
      return{
        pages:[]
      }
    },
    watch: {
      source(){
        
        this.pages = range(1,this.source.last_page + 1)
        
      }
    },
    methods:{
      /*
      Desencadena el evento llamdo navigate y pasa como parametro la pagina a la cual quiere acceder (comunicacion entre componentes)
      */
      navigate(evnt,page){
        evnt.preventDefault()
        this.$emit('navigate',page)
      },
      navigateNextPrev(evnt,page){
        
        if(page == 1 || this.source.last_page + 1 == page){
            
        }else{
          this.$emit('navigate',page)
        }
        
    }
    }
}
</script>
