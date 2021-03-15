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
            <b-button
                           v-if="findPermission(1)"
              @click="addItem()"
              v-scroll-to="'#formData'"
              variant="primary"
            >
              <v-icon>mdi-plus</v-icon>New Record
            </b-button>
          </v-card-title>

          <v-data-table
            :headers="headers"
            :items="records"
            :search="search"
            item-key="id"
          >
            <template v-slot:item.permissions="{ item }">
              
              <span v-if="item.permissions !== null" v-for="i in item.permissions">
                {{ i.permission.title }}
              </span>
            </template>
            <template v-slot:item.actions="{ item }" >
              <a @click="deleteItem(item)"                v-if="findPermission(3)"  class="btn btn-icon btn-light btn-sm"
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
    <div class="row"
          v-if="findPermission(1)"
>
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
                  name="Name"
                  rules="required"
                  v-slot="validationContext"
                >
                  <b-form-group label="name">
                    <b-form-input
                      v-model="form.title"
                      :state="getValidationState(validationContext)"
                      aria-describedby="name"
                      type="text"
                    ></b-form-input>

                    <b-form-invalid-feedback id="name">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
                <validation-provider
                  name="permissions"
                  rules="required"
                  v-slot="validationContext"
                >
                  <b-form-group label="Permissions">
                    <b-form-select
                      v-model="form.permissions"
                      :options="permissions"
                      multiple
                    ></b-form-select>
                    <b-form-invalid-feedback id="permissions">
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
      selected: [],
      permissions: [],
      edit: false,
      // type_id:window.localStorage.getItem("type_id"),
      record_id: "",
      form: {
        title: null,
        permissions: [],
        id: null,
      },

      search: "",
      headers: [
        {
          text: "ID",
          align: "start",
          sortable: false,
          value: "value",
        },
        { text: "Role", value: "text" },
        { text: "Permissions", value: "permissions", sortable: false },
        { text: "Actions", value: "actions", sortable: false },
      ],
    };
  },
  mounted() {
    this.$store.dispatch(SET_BREADCRUMB, [{ title: "Roles", route: "roles" }]);
  },
  created() {
    this.getRecords();
  },
  methods: {
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
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    resetForm() {
      this.form = {
        id: null,
        name: null,
      };
      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
    getRecords() {
      this.$ApiService
        .get("/Roles")
        .then((response) => {
          this.records = response.data.data.Roles;
          this.permissions = response.data.data.Permissions;
        })
        .catch((err) => {
          Vue.$toast.error("get records failed");
          console.log(err);
        });
    },
    onSubmit() {
      console.log("dd");
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
        .post("/Role", this.form)
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
      this.form.name = item.text;
      //this.form.per = item.type_id;
    },

    update() {
      this.$ApiService
        .put(`/Role/${this.record_id}`, this.form)
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
              .delete(`/Role/${item.value}`)
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
