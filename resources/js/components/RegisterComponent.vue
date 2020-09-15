<template>
  <v-app id="inspire">
    <v-main>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                color="green"
                dark
                flat
              >
                <v-toolbar-title>Register form</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
           <v-progress-linear
            :active="loading"
            :indeterminate="loading"
            color="teal">
            </v-progress-linear>
              <v-card-text>
                <v-form>
                 <v-text-field
                    label="Username"
                    name="username"
                    prepend-icon="mdi-account"
                    type="text"
                    v-model="name"
                  ></v-text-field>

                  <v-text-field
                    label="Email"
                    name="email"
                    prepend-icon="mdi-email"
                    type="text"
                    v-model="email"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="password"
                  ></v-text-field>
                   <v-text-field
                    id="confirm_password"
                    label="Confirm Password"
                    name="confirm_password"
                    prepend-icon="mdi-lock"
                    v-model="confirm_password"
                    type="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text large color="green" @click="redirectLogin()">Already Registered,Login Here</v-btn>
                <v-btn color="green" @click="registerUser()">Register</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
      <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="blue"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>
<script>
export default {
    data(){
        return {
            name: "",
            email: "",
            password: "",
            confirm_password: "",
            loading: false,
            snackbar: false,
            text: '',
            timeout: 2000,
        }
    },
    methods: {
        redirectLogin:function(){
            this.$router.push("login");
        },
         registerUser: function(){
            axios.interceptors.request.use((config)=> {
                    this.loading = true
                    return config;
                }, function (error) {
                    this.loading = false;
                    return Promise.reject(error);
                });

            axios.interceptors.response.use((response)=> {
                this.loading = false
                return response;
            }, function (error) {
                this.loading = false;
                return Promise.reject(error);
            });
            if(this.email === "" || this.password === "" || this.name === ""){
              this.snackbar = true;
              this.text = "Please Enter Values In that Fields";
            }else if(!this.password === this.confirm_password){
              this.snackbar = true;
              this.text = "Password Not Match, please Try Again";
            }
            else{
            axios.post('/api/authentication/register',{"name":this.name, "email": this.email, "password": this.password, "password_confirmation": this.confirm_password})
            .then(res => {
                console.log(res);
                if(res.data.status){
                    this.text = res.data.message;
                    this.snackbar = true;
                    this.$router.push("login");
                }else{
                    this.text = res.data.message;
                    this.snackbar = true;
                }
            })
            .catch((error) => console.log(error));
            }
         }
    }

}
</script>
<style scoped>
</style>
