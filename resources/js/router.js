import Vue from 'vue';
import VueRouter from 'vue-router';
import AdminComponent from './components/AdminComponent';
import LoginComponent from './components/LoginComponent';
import RegisterComponent from './components/RegisterComponent';
import PermanentDelete from './components/PermanentDelete';
Vue.use(VueRouter);
const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
      path: '/register',
      component: RegisterComponent
    },
   {
       path: '/login',
       component: LoginComponent,
       beforeEnter: (to,from, next) =>{
        if(localStorage.getItem("token")){
          next("/admin");
        }else{
            next();
        }
    }
   },{
    path: '/admin',
    component: AdminComponent,
    beforeEnter: (to,from, next) =>{
        if(localStorage.getItem("token")){
          next();
        }else{
            next("/login");
        }
    }
   },
   {
     path: '/permanent_delete',
     component: PermanentDelete,
     beforeEnter: (to,from, next) =>{
        if(localStorage.getItem("token")){
          next();
        }else{
            next("/login");
        }
    }
   }
]
export default new VueRouter({routes})
