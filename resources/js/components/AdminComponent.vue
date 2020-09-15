<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <v-list-item link>
          <v-list-item-action>
            <v-icon large  color="green">mdi-account-circle</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ username }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon large  color="green">mdi-delete-forever</v-icon>
          </v-list-item-action>
          <v-list-item-content @click="PermanentDelete()">
            <v-list-item-title > Permanent Delete</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon large  color="green">mdi-exit-to-app</v-icon>
          </v-list-item-action>
          <v-list-item-content @click="userLogout()">
            <v-list-item-title > Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="green"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Book  System</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container
        class="fill-height"
        fluid
      >
      <v-row>
        <v-col cols="12">
        <v-data-table
          :headers="headers"
          :items="desserts"
          sort-by="book_name"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar flat color="white">
              <v-toolbar-title>Books Details</v-toolbar-title>
              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>
              <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    color="green"
                    dark
                    class="mb-2"
                    v-bind="attrs"
                    v-on="on"
                  >Add Book</v-btn>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                  </v-card-title>

                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12">
                          <v-text-field v-model="editedItem.book_name" label="Book Name"></v-text-field>
                        </v-col>
                        <v-col cols="12" >
                          <v-text-field v-model="editedItem.book_description" label="Book Description"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                          <v-text-field v-model="editedItem.book_author" label="Book Author"></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon
              color="green"
              medium
              class="mr-2"
              @click="editItem(item)"
            >
              mdi-pencil
            </v-icon>
            <v-icon
              color="red"
              medium
              @click="deleteItem(item)"
            >
              mdi-delete
            </v-icon>
          </template>
          <template v-slot:no-data>
            <v-btn color="green" @click="initialize">Reset</v-btn>
          </template>
        </v-data-table>
        </v-col>
        </v-row>
      </v-container>
    </v-main>
    <v-footer
      color="green"
      app
    >
      <span class="white--text">&copy; 2020</span>
    </v-footer>
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
    props: {
      source: String,
    },
        data: () => ({
           drawer: null,
           text: '',
           snackbar: false,
           username: localStorage.getItem('user'),
           dialog: false,
           timeout: 2000,
           headers: [

        { text: 'Book Name', value: 'book_name' },
        { text: 'Book Description', value: 'book_description' },
        { text: 'Book Author', value: 'book_author' },
        { text: 'Book Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        book_id: '',
        book_name: '',
        book_description: '',
        book_author: '',
      },
      defaultItem: {
        book_id:'',
        book_name: '',
        book_description: '',
        book_author: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      PermanentDelete:function(){
        this.$router.push('permanent_delete');
      },
      userLogout: function(){
          const options = this.getOptions();
        axios.get('/api/authentication/logout',options)
            .then(res => {
                if(res.data.status){
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    this.setSnackBar(res.data.message);
                    this.$router.push("login");
                }else{
                    this.setSnackBar(res.data.message);
                    this.$router.push("admin");
                }
            })
            .catch(err => console.log(err))
      },
      initialize () {
        const options =  this.getOptions();
         axios.get('/api/book',options)
         .then((res)=>{
             console.log(res.data);
             this.desserts = res.data.book;
             })
         .catch(err=> console.log(err));
      },

      editItem (item) {
        console.log(item);
        this.editedIndex = this.desserts.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },
      deleteItem (item) {
        const options = this.getOptions();
        if(confirm('Are you sure you want to delete this item?')){
           axios.delete('/api/book/'+item.id,options)
           .then(res=>{
               if(res.data.status){
                   this.setSnackBar(res.data.message);
                   this.$router.go();
               }else {
                   this.setSnackBar(res.data.message);
                   this.$router.go;
               }

           })
           .catch(err=>console.log(err));
        }
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
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
      save () {
         const options = this.getOptions();
        if (this.editedIndex > -1) {
            console.log(this.editedItem);
         axios.patch('/api/book/'+this.editedItem.id,{ "book_name": this.editedItem.book_name,"book_description": this.editedItem.book_description,"book_author": this.editedItem.book_author},options)
         .then((res)=>{
              if(res.data.status){
                  this.setSnackBar(res.data.message);
                   this.$router.go();
              }else{
                  this.setSnackBar(res.data.message);
                  this.$router.go();
              }

         })
         .catch(err=>console.log(err));
        } else {
            if(this.editedItem.book_name === "" || this.editedItem.book_description === "" || this.editedItem.book_author === ""){
               this.setSnackBar("please Fill These Field");
            }else{
           axios.post('/api/book',this.editedItem,options)
           .then((res)=>{
                if(res.data.status){
                  this.setSnackBar(res.data.message);
                   this.$router.go();
              }else{
                  this.setSnackBar(res.data.message);
                  this.$router.go();
              }
           })
           .catch(err=>console.log(err));
            }
        }
        this.close()
      },
    },
  }
</script>
