<template>
<div>
    <div>
       <v-app-bar
      color="deep-purple"
    >
      <v-toolbar-title style="text-align:center">Permanently Delete Book And Restore Deleted Book</v-toolbar-title>


      <v-menu
        left
        bottom
      >
      </v-menu>
    </v-app-bar>
 </div>

  <v-row style="min-width=600px; margin:auto;" >
  <v-col>
  <v-simple-table >
    <template v-slot:default>
      <thead>
        <tr>
          <th >Book Name</th>
          <th>Book Descriptipn</th>
          <th>Book Author</th>
          <th>Book Actions</th>
        </tr>
      </thead>
      <tbody>
          <tr v-if="dataCheck" style="text-align:center"> No Book Are There For Permanent Delete Or Restore</tr>
        <tr v-for="item in desserts" :key="item.name">
          <td style="text-align:center">{{ item.book_name }}</td>
          <td style="text-align:center">{{ item.book_description }}</td>
          <td style="text-align:center">{{ item.book_author }}</td>
          <td style="text-align:center">
            <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                    color="error"
                    v-bind="attrs"
                    medium
                    @click="PermanentDeleteItem(item)"
                    v-on="on"
                    >mdi-delete-forever</v-icon>
                </template>
                <span>Delete Permanently</span>
                </v-tooltip>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                    color="error"
                    v-bind="attrs"
                    medium
                    @click="RecoverDeleteItem(item)"
                    v-on="on"
                    >mdi-restore</v-icon>
                </template>
                <span>Restore</span>
                </v-tooltip>
            </td>
        </tr>
      </tbody>
    </template>
  </v-simple-table>
      </v-col>
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
  </v-row>
 </div>
</template>

<script>
export default {
      data () {
      return {
        desserts: [],
        text: '',
        snackbar: false,
        timeout: 2000,
        dataCheck: false
      }
    },
    created(){
        this.initialize();
    },
    methods: {
         getOptions(){
         const options = {
            headers: {'Authorization': localStorage.getItem("token")}
            };
            return options;
      },
       setSnackBar(text){
          this.text = text;
          this.snackbar = true;
      },
        initialize:function(){
           const options = this.getOptions();
           axios.get('/api/books/allSoftDeleteBook',options)
         .then((res)=>{
             console.log(res.data.book.length);
             if(res.data.book.length > 0){
             this.desserts = res.data.book;
             }else {
                 this.dataCheck = true;
                this.setSnackBar("No Deleted Book");
             }
             })
         .catch(err=> console.log(err));
        },
        PermanentDeleteItem:function(item){
             const options = this.getOptions();
           axios.get('/api/books/permenentDelete/'+item.id,options)
         .then((res)=>{
             console.log(res.data);
             if(res.data.status){
                this.setSnackBar(res.data.message);
                this.$router.go();
             }else{
                 this.setSnackBar(res.data.message);
                 this.$router.go();
             }
             })
         .catch(err=> console.log(err));
        },
        RecoverDeleteItem:function(item){
            const options = this.getOptions();
           axios.get('/api/books/restore/'+item.id,options)
         .then((res)=>{
             console.log(res.data);
             if(res.data.status){
                this.setSnackBar(res.data.message);
                this.$router.go();
             }else{
                 this.setSnackBar(res.data.message);
                 this.$router.go();
             }
             })
         .catch(err=> console.log(err));
        }

    }
  }
</script>

<style scoped>

</style>
