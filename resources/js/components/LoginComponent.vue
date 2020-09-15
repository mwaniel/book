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
                <v-toolbar-title>Login form</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-progress-linear
                :active="loading"
                :indeterminate="loading"
                color="teal"
    ></v-progress-linear>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Email"
                    name="email"
                    v-model = "email"
                    prepend-icon="mdi-account"
                    type="text"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    v-model="password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                  ></v-text-field>
                  <v-checkbox v-model="remember_me" label="Remember Me" color="green"></v-checkbox>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text large color="green" @click="redirectToRegister()">Register Yourself From Here</v-btn>
                <v-btn color="green" @click="login()">Login</v-btn>
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
            email: "",
            password: "",
            loading: false,
            remember_me: true,
            snackbar: false,
            text: '',
            timeout: 2000,
        }
    },
    methods: {
        redirectToRegister:function(){
          this.$router.push('register');
        },
        login: function(){
            console.log(this.email+ this.password);
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
            if(this.email === "" || this.password === ""){
              this.snackbar = true;
              this.text = "Please Enter Values In that Fields";
            }else{
            axios.post('/api/authentication/login',{ "email": this.email, "password": this.password, "remember_me": this.remember_me},{'Accept':"application/json"})
            .then(res => {
                console.log(res);
                if(res.data.status){
                    localStorage.setItem("token", res.data.token_type+" "+ res.data.token);
                    localStorage.setItem("user", res.data.user.name);
                    this.$router.push("admin");
                }else{
                    this.text = res.data.message;
                    this.snackbar = true;
                }
            })
            .catch((error) => console.log(error.response.message));
            }
        }
    }

}
</script>
<style scoped>
</style>
