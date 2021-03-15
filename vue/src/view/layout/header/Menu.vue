<template>
  <ul class="menu-nav">
   
     <router-link
      to="/users"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[
          isActive && 'menu-item-active',
          isExactActive && 'menu-item-active',
        ]"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-text"> Users </span>
        </a>
      </li>
    </router-link>
 <router-link
      v-if="findPermission(4)"
      to="/roles"
      v-slot="{ href, navigate, isActive, isExactActive }"
    >
      <li
        aria-haspopup="true"
        data-menu-toggle="hover"
        class="menu-item"
        :class="[
          isActive && 'menu-item-active',
          isExactActive && 'menu-item-active',
        ]"
      >
        <a :href="href" class="menu-link" @click="navigate">
          <span class="menu-text"> Roles </span>
        </a>
      </li>
    </router-link>

    <!--  end of main menu -->
  </ul>
</template>

<script>
export default {
  name: "KTMenu",
  data() {
    return {
      permissions:[],
    };
  },
  mounted() {
   
  },

   created() {
 console.log(window.localStorage.getItem("user_id")+"DDDD");
    var id=window.localStorage.getItem("user_id");
    this.$ApiService
      .get(`/getUser/${id}`)
      .then((response) => {
        this.permissions =response.data.data[0].role_permission;
        console.log(this.permissions[0].permission_id);
       
var per_array="";
        for(var x=0;x<this.permissions.length;x++){
          per_array=per_array+this.permissions[x].permission_id+",";

        }
        window.localStorage.setItem("permissions",per_array);
      })
      .catch((err) => {
       console.log("err="+err);
      });  },
  methods: {
    
    findPermission(per_id){

      var permissions=window.localStorage.getItem("permissions");
      permissions=permissions.split(',');
     for(var x=0;x<permissions.length;x++){
        if(permissions[x]==per_id)
        return true;
     }
      return false;

      
    },
    hasActiveChildren(match) {
      return this.$route["path"].indexOf(match) !== -1;
    },
  },
};
</script>
