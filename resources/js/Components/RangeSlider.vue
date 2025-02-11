<template>

  <div :class="`wrapper-sr-${id}` " class="py-3 px-2" dir="ltr">
    <tc-range-slider
        class="theme-glass"
        theme="glass"
        :value-label="`#slider-label-${id}`"
        :id="`${id}`"
        :value="value"
        step="1"
        slider-bg-fill="#000"
        moving-tooltip="true"
        moving-tooltip-distance-to-pointer="25"
        moving-tooltip-width="35"
        moving-tooltip-height="30"
        moving-tooltip-bg=""
        moving-tooltip-text-color="#fff"
        marks="true"
        marks-count="3"
        marks-values-count="3"
        marks-color="#475569"
        marks-values-color="#475569"
        generate-labels="false"
        generate-labels-units=""
        :round="0"
        slider-width="100%">

    </tc-range-slider>
    <!--    <div :id="`slider-label-${id}`"></div>-->
  </div>

</template>

<script>
import 'toolcool-range-slider/dist/plugins/tcrs-moving-tooltip.min';
import 'toolcool-range-slider/dist/plugins/tcrs-generated-labels.min';
import 'toolcool-range-slider/dist/plugins/tcrs-binding-labels.min';
import 'toolcool-range-slider/dist/plugins/tcrs-marks.min';

import 'toolcool-range-slider';

export default {
  data() {
    return {}
  },
  props: ['id', 'value', 'min', 'max'],
  emits: ['update:modelValue', 'change'],
  mounted() {
    this.initSlider();
  },
  methods: {
    initSlider() {
      const wrapper = document.getElementById(`wrapper-sr-${this.id}`);

      this.slider = document.getElementById(`${this.id}`);
      if (this.slider)
        this.slider.addEventListener('change', (evt) => {
          this.$emit("change", evt.detail.value);
        })
      // this.set(0, 100, 0)
    },
    set(min, max, value) {
      // if (!this.slider) return
      this.slider.min = min;
      this.slider.max = max;
      this.slider.marksCount = max + 1
      this.slider.step = 1;
      this.slider.round = 0;
      // this.slider.marksCount = max != null && max > 1 ? 2  /*Math.round(this.slider.max - this.slider.min) */ : 1
      this.slider.marksValuesCount = this.slider.marksCount
      this.slider.value = parseFloat(value);


      // console.log(min, max, parseInt(value))

    }
  }
}


</script>
<style>


.theme-glass {
  --pointer-bg: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(206, 206, 206, 1) 48%, rgba(255, 255, 255, 0) 100%);
  --pointer-bg-hover: linear-gradient(to bottom, rgba(206, 206, 206, 1) 0%, rgba(255, 255, 255, 0) 48%, rgba(206, 206, 206, 1) 100%);
  --pointer-bg-focus: linear-gradient(to bottom, rgba(206, 206, 206, 1) 0%, rgba(255, 255, 255, 0) 48%, rgba(206, 206, 206, 1) 100%);
  --pointer-border-radius: 3px
}


.theme-ruler .panel {
  background-size: .4rem 100%;
  background-repeat: repeat-x;
  background-image: linear-gradient(90deg, rgba(0, 0, 0, 0) calc(50% - 2px), var(--panel-bg-fill, #000) 50%, rgba(0, 0, 0, 0) calc(50% + 2px))
}

</style>