<template>
  <!-- begin:: Footer -->
  <div
    class="footer kt-grid__item bgi-position-center-center bgi-size-cover bgi-no-cover"
    id="kt_footer"
    style="background-image: url('media/bg/bg-10.jpg');"
  >
   
    
    <!--begin::Container-->
    <div class="container py-8">
      <div
        class="d-flex align-items-center justify-content-between flex-lg-row flex-column"
      >
        <!--begin::Copyright-->
        <div class="d-flex align-items-center order-lg-1 order-2">
          <img
            alt="Logo"
            src="media/logos/plogo.jpg"
            class="logo-sticky max-h-35px"
          />
          <span class="text-muted font-weight-bold mx-2">2021© All rights reserved</span>
        
        </div>
        <!--end::Copyright-->
      
      </div>
    </div>
    <!--end::Container-->
  </div>
  <!-- end:: Footer -->
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "KTFooter",
  computed: {
    ...mapGetters(["layoutConfig"]),

    /**
     * Check if footer container is fluid
     */
    widthFluid() {
      return this.layoutConfig("footer.width") === "fluid";
    }
  }
};
</script>
