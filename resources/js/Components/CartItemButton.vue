<template>

  <div class="flex    flex-col ">

    <div class="flex  hover:cursor-pointer">
      <div
          @click.prevent="!show? show=true:  !loading? edit({variation_id:productId,qty:inCart,price_type:priceType}):null "
          :class="  show ?'rounded-s-md':'rounded-md'"
          class="border grow   flex justify-center items-center   border-primary-500 text-primary-500 p-2  hover:bg-primary-400 hover:text-white">
        <ShoppingCartIcon v-if=" !show" :class="{'border-e pe-1':inCart}" class="w-7 h-6  "/>
        <div v-if="inCart && !show" class="mx-2 font-bold text-primary-700">{{ inCart }}</div>
        <div v-if=" show" class="mx-2 text-center" @click="">
          <div v-if="!loading"> {{ __('accept') }}</div>
          <LoadingIcon v-else class="w-6 h-6 fill-primary-500"/>
        </div>
      </div>
      <div v-if="show && !loading" @click.prevent="inCart=inCart; show=false"
           class=" text-white flex   rounded-e-md bg-danger-600 hover:bg-danger-500 hover:text-white">
        <XMarkIcon class="w-8  mx-2"/>
      </div>
    </div>
    <div v-if="false && show" class="flex items-center gap-1 justify-center text-neutral-500 text-sm p-2" dir="ltr">
      <div class="text-neutral-700">{{ asPrice(selectedPrice) }}</div>
      <XMarkIcon class="w-4   "/>
      <div class="text-neutral-700">{{ inCart }}</div>
      <Bars2Icon class="w-4  "/>
      <div class="text-neutral-700 ">{{ asPrice(inCart * selectedPrice) }}</div>
    </div>
    <Transition name="fade">
      <div v-show=" show">
        <div class=" h-full  py-3 my-1 flex justify-center items-stretch text-primary-500 "
        >
          <div @click.prevent="plus()"
               class=" items-center flex   border rounded-s border-primary-500 hover:bg-primary-500 hover:text-white hover:cursor-pointer">
            <PlusIcon
                class="w-6  mx-3 "/>
          </div>
          <input @click.prevent type="number" min="0" v-model="inCart"
                 class="  flex w-full shrink text-lg p-1 border text-center focus:border-primary-500 border-primary-500 focus:ring-primary-500">
          <div v-if="false" class="flex   grow">
            <RangeSlider :ref="`rs-${productId}`"
                         :min="sliderData.min"
                         :max=" sliderData.max"
                         @change="($e)=>updateRange( $e)" class="w-full" :value="inCart"
                         :id="`rs-${productId}`"></RangeSlider>
          </div>
          <div @click.prevent="minus()"
               class="items-center flex  border rounded-e border-primary-500 hover:bg-primary-500 hover:text-white hover:cursor-pointer">
            <MinusIcon
                class="w-6 mx-3"/>
          </div>
        </div>
        <div v-if="false && show && $page.props.price_types">
          <div class="    flex justify-center my-3  text-primary-500  rounded bg-primary-100  ">
            <div @click.prevent="priceType=item" v-for="item,idx in $page.props.price_types"
                 class="   grow justify-center   ">
              <input :checked="item==priceType||null" v-model="priceType" type="radio" name="pt"
                     :id="`pt-${item}`"
                     :value="item"
                     class="peer hidden"/>
              <label :for="`pt-${item}`"
                     :class="idx==0? 'rounded-s' : idx==$page.props.price_types.length-1? 'rounded-e':'rounded-0'"
                     class="duration-300 transition-all     flex justify-center text-center cursor-pointer select-none   p-2  peer-checked:bg-primary-500 peer-checked:font-bold peer-checked:text-white">
                {{ __(item) }}
              </label>
            </div>

          </div>
        </div>

      </div>
    </Transition>

  </div>

</template>

<script>
import {
  ChevronDownIcon,
  ChevronUpIcon,
  ShoppingCartIcon,
  PlusIcon,
  MinusIcon,
  XMarkIcon,
  Bars2Icon,
} from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import RangeSlider from "@/Components/RangeSlider.vue";


export default {
  name: "CartItemButton",
  data() {
    return {
      inCartOld: 0,
      inCart: 0,
      priceType: this.$page.props.price_types && this.$page.props.price_types.length > 0 ? this.$page.props.price_types[0] : null,
      selectedPrice: 0,
      show: false,
      modal: null,
      loading: false,
      cart: this.$page.props.cart,
      sliderData: {min: null, max: null},
    }
  },
  expose: ['update:cart', 'qtyChanged'],
  props: ['productId', 'inShop', 'link', 'prices', 'price'],
  components: {
    TextInput,
    ChevronDownIcon,
    ChevronUpIcon,
    ShoppingCartIcon,
    PlusIcon,
    MinusIcon,
    LoadingIcon,
    XMarkIcon,
    RadioGroup,
    RangeSlider,
    Bars2Icon,
  },
  mounted() {
    // if (!window.Modal) {
    //   window.Modal = Modal;
    // initTE({Modal});
    // }

    this.setInCartQty();
    // this.setPrices();
    this.inCartOld = this.inCart;

    this.emitter.on('updateCart', (cart) => {
      this.cart = cart;
      this.setInCartQty();
    });
  },
  methods: {
    setShowPrice() {
      const qty = this.inCart
      let showPrice
      let showDiscount
      const findDiscount = this.prices?.find(pr =>
          Number(pr.from) <= qty && Number(pr.to) >= qty
      )

      if (findDiscount) {
        showPrice = Math.round((100 - findDiscount.discount) * this.price / 100)
        showDiscount = `${findDiscount.discount}%`

      } else {
        showPrice = this.price
      }
      this.$nextTick(() => {

        this.$emit('qtyChanged', Number(showPrice), Number(showDiscount))
      })

    },
    setPrices() {
      for (const i in this.prices) {
        if (this.sliderData.min == null || this.sliderData.min > this.prices[i].from) {
          this.sliderData.min = this.prices[i].from;
        }
        if (this.sliderData.max == null || this.sliderData.max < this.prices[i].to) {
          this.sliderData.max = this.prices[i].to;
        }
      }
      if (true || this.$refs[`rs-${this.productId}`]) {
        this.$refs[`rs-${this.productId}`].set(this.sliderData.min, this.sliderData.max, this.inCart);

        const shows = (this.prices || []).map(i => i.from) || [];
        shows.push(this.sliderData.max)
        const points = document.getElementById(`rs-${this.productId}`).shadowRoot.querySelectorAll('.mark-points .mark');
        const values = document.getElementById(`rs-${this.productId}`).shadowRoot.querySelectorAll('.mark-values .mark-value');
        points.forEach((i) => {
          i.style.opacity = 0;
          shows.forEach(el => {
            if (i.classList.contains(`mark-${el}`))
              i.style.opacity = 1;

          })
        })
        values.forEach((i) => {
          i.style.opacity = 0;
          shows.forEach(el => {
            if (el != 0 && (i.classList.contains(`mark-value-${el}`)))
              i.style.opacity = 1;
            i.style.fontSize = '12px';
            i.style.transformOrigin = 'center left';
            i.style.transform = 'rotate(90deg) translateX(-90%)';

          })
        })
      }
    },
    updateRange(count) {
      this.inCart = count;
      this.selectedPrice = ((Array.isArray(this.prices) ? this.prices : []).filter((i) => i.from <= count && i.to >= count)[0] || {price: 0}).price
    },
    setInCartQty() {
      this.inCart = 0

      if (this.cart && this.cart.orders && this.cart.orders.length > 0)
        for (let ix in this.cart.orders) {
          for (let idx in this.cart.orders[ix].shipments) {
            for (let id in this.cart.orders[ix].shipments[idx].items) {
              if (this.cart.orders[ix].shipments[idx].items[id].cart_item.variation_id == this.productId) {
                this.inCart = this.cart.orders[ix].shipments[idx].items[id].cart_item.qty;
                this.priceType = this.cart.orders[ix].shipments[idx].items[id].cart_item.price_type;

                this.inCart = this.inCart ? parseFloat(this.inCart) : 0;
                this.setShowPrice()

                break;
              }
            }
          }
        }
    }
    ,
    isInt(value) {
      return (typeof value === 'number' &&
          isFinite(value) &&
          Math.floor(value) === value);

    }
    ,
    plus() {
      if (this.isInt(this.inCart))
        this.inCart++;
      else this.inCart = 0;
    }
    ,
    minus() {
      if (this.isInt(this.inCart) && this.inCart > 1)
        this.inCart--;
      else this.inCart = 0;
    }
    ,
    edit(params) {
      this.loading = true;
      window.axios.patch(this.link || route('cart.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);
            }

            if (response.data.cart) {
              this.updateCart(response.data.cart)
            }

            this.show = false;
          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {

            }
            this.showToast('danger', this.error);

          })
          .finally(() => {
            // always executed
            this.setInCartQty();
            this.loading = false;

          });
    }
    ,

  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>