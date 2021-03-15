<template>
  <!--begin::Mixed Widget 1-->
  <div class="card card-custom mt-23 bg-gray-100 card-stretch gutter-b">
    <!--begin::Header-->

    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body  p-0 position-relative overflow-hidden">
      <!--begin::Chart-->
      <apexchart
        class="card-rounded-bottom bg-danger"  
        type="area"
      ></apexchart>
      <!--end::Chart-->
      <!--begin::Stats-->
      <div class="card-spacer mt-5">
        <!--begin::Row-->
        <div class="row m-0">
          <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
              <inline-svg src="media/svg/icons/Media/Equalizer.svg" />
            </span>
       <router-link to="/faqs">    <h3 class="text-warning font-weight-bold">{{faqs}} FAQs</h3></router-link>
          </div>
          <div class="col bg-light-primary px-6 py-8 rounded-xl mb-7">
            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
              <inline-svg src="media/svg/icons/Communication/Add-user.svg" />
            </span>
           <router-link to="/landContact"> <h3 class="text-primary font-weight-bold">{{contact}}              Users's Contact Requests</h3>
           </router-link>
            
          </div>
        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <div class="row m-0">
          <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
              <inline-svg src="media/svg/icons/Design/Layers.svg" />
            </span>
          <router-link to="/articles">  <h3 class="text-danger font-weight-bold">{{articles}} Articels</h3></router-link>
            
          </div>
          <div class="col bg-light-success px-6 py-8 rounded-xl">
            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
              <inline-svg src="media/svg/icons/Communication/Urgent-mail.svg" />
            </span>
         <router-link to="/contacts">   <h3 class="text-success font-weight-bold">{{feedback}}               Users's Feedbacks</h3></router-link>
          </div>
        </div>
        <!--end::Row-->
      </div>
      <!--end::Stats-->
      <!--end::Body-->
    </div>
    <!--end::Mixed Widget 1-->
  </div>
</template>

<script>

import { mapGetters } from "vuex";

export default {
  name: "widget-1",
  components: {
    
  },
  data() {
    return {
      faqs:0,
      articles:0,
      contact:0,
      feedback:0,
    };
  },
  computed: {
    ...mapGetters(["layoutConfig"])
  },
  mounted() {
    // reference; kt_mixed_widget_1_chart

    this.$ApiService
        .get("/statistics")
        .then((response) => {
          
          this.contact = response.data.data.contact;
          this.feedback = response.data.data.feedback;
          this.articles = response.data.data.articles;
          this.faqs = response.data.data.faqs;
        })
        .catch((err) => {
          console.log(err);
        });
    
  }
};
</script>
