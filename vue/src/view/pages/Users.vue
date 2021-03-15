<template>
  <div>
    <vue-confirm-dialog></vue-confirm-dialog>

    <div class="row">
      <div class="col-md-12" ref="table">
        <v-card>
          <v-card-title>
            Records
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>

            <v-spacer></v-spacer>
          
          </v-card-title>

          <v-data-table
            :headers="headers"
            :items="records"
            :search="search"
            item-key="id"
          >
            
            <template v-slot:item.actions="{ item }">
              <a
                v-if="findPermission(2)"
                @click="editItem(item)"
                v-scroll-to="'#formData'"
                class="btn btn-icon btn-light btn-sm mx-3"
                ><span class="svg-icon svg-icon-md svg-icon-primary"
                  ><svg
                    version="1.1"
                    viewBox="0 0 24 24"
                    height="24px"
                    width="24px"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <title xmlns="http://www.w3.org/2000/svg">
                      Edit Record
                    </title>

                    <defs xmlns="http://www.w3.org/2000/svg"></defs>
                    <g
                      xmlns="http://www.w3.org/2000/svg"
                      id="Stockholm-icons-/-Communication-/-Write"
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <rect
                        id="bound"
                        x="0"
                        y="0"
                        width="24"
                        height="24"
                      ></rect>
                      <path
                        d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                        id="Path-11"
                        fill="#000000"
                        fill-rule="nonzero"
                        transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "
                      ></path>
                      <path
                        d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                        id="Path-57"
                        fill="#000000"
                        fill-rule="nonzero"
                        opacity="0.3"
                      ></path>
                    </g></svg></span></a
              ><a
               v-if="findPermission(3)"
                @click="deleteItem(item)"
                class="btn btn-icon btn-light btn-sm"
                ><span class="svg-icon svg-icon-md svg-icon-danger"
                  ><svg
                    version="1.1"
                    viewBox="0 0 24 24"
                    height="24px"
                    width="24px"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <title xmlns="http://www.w3.org/2000/svg">
                      Delete Record
                    </title>

                    <defs xmlns="http://www.w3.org/2000/svg"></defs>
                    <g
                      xmlns="http://www.w3.org/2000/svg"
                      id="Stockholm-icons-/-General-/-Trash"
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <rect
                        id="bound"
                        x="0"
                        y="0"
                        width="24"
                        height="24"
                      ></rect>
                      <path
                        d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                        id="round"
                        fill="#000000"
                        fill-rule="nonzero"
                      ></path>
                      <path
                        d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                        id="Shape"
                        fill="#000000"
                        opacity="0.3"
                      ></path>
                    </g></svg></span
              ></a>
            </template>
          </v-data-table>
        </v-card>
      </div>
    </div>

    <hr />
    <div class="row" >
      <div id="formData" class="col-md-12">
        <v-card>
          <v-card-title>Fill in Form Data</v-card-title>
          <v-card-text>
            <validation-observer ref="observer" v-slot="{ handleSubmit }">
              <b-form
                enctype="multipart/form-data"
                @submit.stop.prevent="handleSubmit(onSubmit)"
              >
                <validation-provider
                  name="Type"
                  rules="required"
                  v-slot="validationContext"
                >
                  <b-form-group label="User Type">
                    <b-form-select
                      v-model="form.type_id"
                      :options="types"
                      class="mb-3"
                      :state="getValidationState(validationContext)"
                      aria-describedby="type_id"
                    >
                      <!-- This slot appears above the options from 'options' prop -->
                      <template v-slot:first>
                        <b-form-select-option :value="null"
                          >-- Please select an option --</b-form-select-option
                        >

                      </template>
                    </b-form-select>
                    <b-form-invalid-feedback id="type_id">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
                 <validation-provider
                  name="Name"
                  rules="required"
                  v-slot="validationContext"
                >
                  <b-form-group label="name">
                    <b-form-input
                    readonly
                      v-model="form.name"
                      :state="getValidationState(validationContext)"
                      aria-describedby="name"
                      type="text"
                    ></b-form-input>

                    <b-form-invalid-feedback id="name">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
               
                  

                <b-button type="submit" variant="success">Save</b-button>
                <b-button class="ml-2" variant="warning" @click="resetForm()"
                  >Reset</b-button
                >
              </b-form>
            </validation-observer>
          </v-card-text>
        </v-card>
      </div>
    </div>
  </div>
</template>

<script>
import { SET_BREADCRUMB } from "@/core/services/store/breadcrumbs.module";
import Vue from "vue";
const VueScrollTo = VueScrollTo;
export default {
  data() {
    return {
      records: [],
      types: [],
      edit: false,
      type_id:window.localStorage.getItem("type_id"),
      permissions:[],
      record_id: "",
      form: {
        type_id: null,
        id: null,
        email: null,
        password: null,
      },

      search: "",
      headers: [
        {
          text: "ID",
          align: "start",
          sortable: false,
          value: "id",
        },
        { text: "Name", value: "name" },
        { text: "Email", value: "email" },
        { text: "Types", value: "user_type.title", sortable: false },
        { text: "Date", value: "created_at" },
        { text: "Actions", value: "actions", sortable: false },
      ],
    };
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [
      { title: "Users", route: "users" },
    ]);
    
  },
  created() {
    this.getRecords();
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    resetForm() {
      this.form = {
        type_id: null,
        id: null,
        name:null,
        email: null,
        password: null,
      };
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
    getRecords() {
     // console.log(window.localStorage.getItem("type_id")+"dddddd");
      this.$ApiService
        .get("/Users")
        .then((response) => {
          this.records = response.data.data.users;
          this.types=response.data.data.user_types;
          this.permissions=response.data.data.users.role_permission;
             // this.findType();

        })
        .catch((err) => {
          Vue.$toast.error("get records failed");
          console.log(err);
        });
    },
    findPermission(per_id){
      var permissions=window.localStorage.getItem("permissions");
      permissions=permissions.split(',');
     //console.log("arr"+permissions);
     for(var x=0;x<permissions.length;x++){
        if(permissions[x]==per_id)
        return true;
     }
              return false;

      
    },
    onSubmit() {
      if (this.edit === false) {
        // add new record
        this.insert();
      } else {
        //update record
        this.update();
      }
    },

    insert() {
      this.$ApiService
        .post("/Users", this.form)
        .then((response) => {
          if (response.data.error == 1) {
            this.resetForm();

            this.$refs.table.scrollIntoView();
            this.getRecords();
            Vue.$toast.success(`${response.data.result}`);
          } else {
            Vue.$toast.error(`${response.data.result}`);
          }
        })
        .catch((err) => {
          Vue.$toast.error("Some error happened, please try again!");
          console.log(err);
        });
    },
    addItem() {
      this.edit = false;
      this.resetForm();
    },
    editItem(item) {
      this.edit = true;
      this.record_id = item.id;
      this.form.name = item.name;
      this.form.type_id = item.type_id;
    },

    update() {
      this.$ApiService
        .put(`/Users/${this.record_id}`, this.form)
        .then((response) => {
          if (response.data.error == 1) {
            this.resetForm();
            this.edit = false;
            this.record_id = "";

            this.$refs.table.scrollIntoView();
            this.getRecords();
            Vue.$toast.success(`${response.data.result}`);
          } else {
            Vue.$toast.error(`${response.data.result}`);
          }
        })
        .catch((err) => {
          Vue.$toast.error("Some error happened, please try again!");
          console.log(err);
        });
    },
    deleteItem(item) {
      this.$confirm({
        title: "Confirmation",
        message: `Are you sure you want to proceed?`,
        button: {
          no: "No",
          yes: "Yes",
        },
        callback: (confirm) => {
          if (confirm) {
            this.$ApiService
              .delete(`/Users/${item.id}`)
              .then((response) => {
                if (response.data.error == 1) {
                  this.$refs.table.scrollIntoView();
                  this.getRecords();
                  Vue.$toast.success(`${response.data.result}`);
                } else {
                  Vue.$toast.error(`${response.data.result}`);
                }
              })
              .catch((err) => {
                Vue.$toast.error("Some error happened, please try again!");
                console.log(err);
              });
          }
        },
      });
    },
  }, // end of methods
}; //end of class
</script>
