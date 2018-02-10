export default function (Vue){

    let authenticatedUser = {};

    Vue.auth = {

        setToken:(token,expiration) =>{
            
            localStorage.setItem('token',token);
            localStorage.setItem('expiration',expiration);    
        
        },

        getToken:() =>{

            var token = localStorage.getItem(token);
            var expiration = localStorage.getItem(expiration);

            if (!token || !expiration) {    
                return null;
            } 
            
            if (Date.now() > parseInt(expiration)) {
                this.destroyToken();
                return null;
            }
            else {
                return null;
            }

        },

        destroyToken:() =>{
            
            localStorage.removeItem('token',token);
            localStorage.removeItem('expiration',expiration);    
        
        },

        isAuthenticated:() => {
            if(this.getToken()){
                return true;
            }else{
                return false;
            }
        },    

        setAuthenticatedUser(data){
            authenticatedUser = data;
          },
          
        getAuthenticatedUser(){
            return authenticatedUser;
        }

    }

    Object.defineProperties(Vue.Prototype,{

        $auth:{
            get : () => {
                return Vue.auth;
            }
        }

    })

    

}

