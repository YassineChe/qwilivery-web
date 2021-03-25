<template>
  <transition name="fade">
    <div v-if="show" class="preloader">
      <img
        class="logo"
        src="https://thumbs.dreamstime.com/b/pin-delivery-logo-icon-design-can-be-used-as-complement-to-125309848.jpg"
      />

      <p>DELIVERY</p>
      <v-progress-circular
        :size="50"
        color="primary"
        indeterminate
      ></v-progress-circular>
    </div>
  </transition>
</template>
<script>
export default {
  name: "preloader",
  data() {
    return {
      show: true,
      attrs: {
        class: "mb-6",
        boilerplate: true,
        elevation: 2,
      },
    };
  },
  mounted() {
    if (Boolean(this.show)) this.showToggle();
  },
  methods: {
    showToggle() {
      setTimeout(() => {
        this.show = false;
      }, 1000);
    },
  },
};
</script>

<style lang="sass" scoped >
.preloader
    display: flex
    flex-direction: column
    align-items: center
    justify-content: center
    position: absolute
    width: 100%
    height: 100%
    background-color: white
    z-index: 9999
    .logo
        width: 12.5rem
        height: 12.5rem
        background: #332333
        background-repeat: no-repeat

    p

        margin-top: 1rem
    .circles
        display: flex
        margin-top: 1rem
        .circle
            width: 1.5rem
            height: 1.5rem
            margin: .75rem
            background-color: red
            border-radius: 50%
            animation-name: scaleIn
            animation-duration: .7s
            transform: scale(0)
            &:nth-child(1)
                animation-delay: 0s
            &:nth-child(2)
                animation-delay: .05s
            &:nth-child(3)
                animation-delay: .1s
            &:nth-child(4)
                animation-delay: .15s
            &:nth-child(5)
</style>