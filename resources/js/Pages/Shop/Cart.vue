<template>
  <Scaffold navbar-theme="light">
    <template v-slot:header>
      <title>{{page=='shipping'?__('address'):__('cart')}}</title>

    </template>


    <div v-if="cart" class="flex flex-col md:flex-row p-2 lg:p-4 lg:max-w-5xl mx-auto">


      <section class=" grow  py-4 border  rounded-lg   md:rounded-e-none   bg-neutral-50">

        <!--        payment section-->
        <div v-if=" page=='payment'"
             class="border-b flex flex-col   rounded-lg mx-4 mt-4 p-2 lg:p-4">
          <div class="text-neutral-400 pb-2">{{ __('payment_method') }}</div>

          <div v-for="(payment,idx) in cart.payment_methods" @click="update({payment_method:payment.key})"
               class="border flex items-center justify-between p-4 cursor-pointer hover:bg-primary-50 "
               :class="`${ payment.key==cart.payment_method?'bg-primary-100 text-primary-700 ':' '} ${idx==cart.payment_methods.length-1 ?'rounded-b-lg ':' '} ${idx==0 ?'rounded-t-lg ':' '}`">
            <div class="">
              <div>{{ payment.name }}</div>
              <div class="text-xs text-gray-400 ">{{ payment.description }}</div>
            </div>
            <CheckCircleIcon v-if="payment.selected" class="w-6  "/>

          </div>
        </div>
        <!--        address section-->
        <div v-if="page=='shipping' || page=='payment'"
             class="border-b flex flex-col shadow  rounded-lg mx-1 mt-4 p-2 lg:p-4">
          <div class="text-neutral-400 pb-2">{{ __('delivery_address') }}</div>


          <AddressSelector v-if="cart.need_address ||  page!='cart' " :editable="page!='cart'" :clearable="true"
                           class=" " ref="addressSelector"
                           @change="update({address_idx:$event})"
                           :error="cart.errors &&   cart.errors.filter((e)=>e.type=='address').length>0?cart.errors.filter((e)=>e.type=='address')[0].message :null"
                           :preload-data=" cart.address " type="cart"/>

          <div v-show="  page=='payment' && cart.orders.length>0 && cart.need_self_receive"
               class="text-primary-500 font-bold py-2">
            {{ __('you_selected_self_receive') }}
          </div>
        </div>
        <div v-if=" cart.orders.length==0" class="w-full p-4  items-center flex flex-col justify-center ">
          <div> {{ __('cart_is_empty') }}</div>
          <Link class="text-primary-500 hover:text-primary-400 cursor-pointer" :href="route('shop.index')"> {{
              __('shop')
            }}
          </Link>
        </div>
        <div v-for="(cart,idx) in cart.orders" class="shadow-md    rounded">
          <div class="p-2">{{ `${__('order')} ${idx + 1}` }}</div>
          <div v-for="( shipment ,id) in cart.shipments"
               :class="{'bg-danger-100':shipment.method.error_message && page!='cart'  }"
               class="     p-2 m-2    ">
            <div v-for="(item,idx) in shipment.items" :key="item.cart_item.variation_id"
                 class="flex p-2  flex-col my-2"
                 :class="{'bg-danger-100':item.cart_item.error_message}">
              <div class="flex items-start" v-if="item.cart_item "
              >
                <div>
                  <Image :src="route('storage.variations')+`/${item.cart_item.variation_id}/thumb.jpg`"
                         :failUrl="route('storage.products')+`/${item.cart_item.product.id}.jpg`"
                         classes="w-32 h-32 object-contain rounded  mx-1 "
                  />
                </div>
                <div v-if=" item.cart_item.product "
                     class="   w-full flex-col p-2 space-y-2 items-start">
                  <div class="flex items-center justify-between">
                    <Link
                        :href=" route( 'variation.view',{id:item.cart_item.product.id,name:item.cart_item.product.name})"
                        class="cursor-pointer hover:text-primary-500">
                      {{ item.cart_item.product.name || '' }}
                    </Link>
                    <div v-if="item.cart_item.product.grade"
                         class="text-sm text-neutral-500 mx-2 ">{{
                        __('grade') + ' ' + item.cart_item.product.grade
                      }}
                    </div>
                  </div>
                  <div class="text-neutral-400 text-sm">{{ item.repo_name }}</div>
                  <div class="flex  items-center text-sm">
                    <!--                <ShoppingBagIcon class="w-5 h-5 text-neutral-500"/>-->
                    <div class="text-neutral-600 mx-1">{{ __('qty') }}:</div>
                    <div class="text-neutral-600 mx-1">{{
                        item.cart_item.qty ? `${parseFloat(item.cart_item.qty)} ${__(item.cart_item.unit)}` : 0
                      }}
                    </div>
                    <div class="text-neutral-400"> {{ getPack(item.cart_item.product.pack_id) }}</div>
                  </div>

                  <div class="flex flex-col  items-start text-sm">
                    <div class="flex"
                         v-if=" false && (item.cart_item.product.prices || []).filter(i => i.type == item.cart_item.price_type && i.from <= item.cart_item.qty && i.to >= item.cart_item.qty).length>0">
                      <div class="text-neutral-500 mx-1">{{ __('price_unit') }}:</div>
                      <div class="text-neutral-700 mx-1">{{
                          asPrice((item.cart_item.product.prices || []).filter(i => i.type == item.cart_item.price_type && i.from <= item.cart_item.qty && i.to >= item.cart_item.qty)[0].price)
                        }}
                      </div>
                      <TomanIcon class="w-5 h-5 text-neutral-400"/>

                    </div>
                    <div class="flex">
                      <div class="text-neutral-500 mx-1">{{ __('discount') }}:</div>
                      <div class="text-neutral-700 mx-1">{{
                          asPrice(item.cart_item.total_discount)
                        }}
                      </div>
                      <TomanIcon class="w-5 h-5 text-neutral-400"/>
                    </div>
                    <div class="flex">
                      <div class="text-neutral-500 mx-1">{{ __('price') }}:</div>
                      <div class="text-neutral-700 mx-1">{{
                          asPrice(Math.round(item.cart_item.total_price))
                        }}
                      </div>
                      <TomanIcon class="w-5 h-5 text-neutral-400"/>
                    </div>
                  </div>
                  <div v-if="true" class="flex  items-center text-sm">
                    <!--                    <div class="text-neutral-600 mx-1">{{ __('weight_unit') }}:</div>-->
                    <!--                    <div class="text-neutral-600 mx-1">{{ parseFloat(item.cart_item.product.weight) }}</div>-->
                    <!--                   -->

                    <div class="text-neutral-600 mx-1">{{ __('total_weight') }}:</div>
                    <div class="text-neutral-600 mx-1">{{
                        parseFloat(item.cart_item.total_weight.toFixed(3))
                      }}
                    </div>
                    <div class="text-neutral-400 mx-1">{{ __('kg') }}</div>

                  </div>
                  <div class="flex items-center   my-2 text-xs ">
                    <Menu

                        v-if="item.cart_item.product.prices?.length" as="div"
                        class="relative inline-block text-start">
                      <div>
                        <MenuButton ref="menuRefs"
                                    class="group bg-success-50 flex text-success  hover:bg-success-100 cursor-pointer font-bold shadow-md rounded-lg   p-2 ">
                          <div> {{
                              (item.cart_item.product.prices?.length)
                                  ? `${item.cart_item.product.prices.length === 1
                                      ? item.cart_item.product.prices[0].discount
                                      : `%${item.cart_item.product.prices[0].discount ?? '0'} ${__('until')} %${item.cart_item.product.prices[item.cart_item.product.prices.length - 1].discount ?? '0'}`
                                  } ${__('discount')}` : ''
                            }}
                          </div>
                          <ChevronDownIcon class="mx-1  h-5 text-success-400 group-hover:text-success-500"
                                           aria-hidden="true"/>
                        </MenuButton>
                      </div>

                      <transition enter-active-class="transition ease-out duration-100"
                                  enter-from-class="transform opacity-0 scale-95"
                                  enter-to-class="transform opacity-100 scale-100"
                                  leave-active-class="transition ease-in duration-75"
                                  leave-from-class="transform opacity-100 scale-100"
                                  leave-to-class="transform opacity-0 scale-95">
                        <MenuItems
                            class="absolute p-2 right-0 z-10   w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black/5 focus:outline-hidden">
                          <div class="py-1">
                            <MenuItem
                                v-for="(price,idx) in item.cart_item.product.prices??[]"
                                :key="`price-item-${item.cart_item.product.id}-${idx}`"
                                v-slot="{ active }">
                              <div class="flex items-center justify-between ">
                                <div class="font-bold text-success animate-pulse">{{
                                    price.discount
                                  }}%
                                </div>
                                <div>{{ price.from }}-{{ price.to }}</div>
                              </div>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                    <div v-if="item.cart_item.product.showDiscount"
                         class="font-bold bg-success-50 text-success rounded-lg shadow-md p-2 bg-white"> {{
                        item.cart_item.product.showDiscount
                      }}
                    </div>
                  </div>
                </div>

              </div>
              <div v-if="item.cart_item.error_message" class="text-danger-600 font-bold text-sm">
                {{ item.cart_item.error_message }}
              </div>
              <div class="flex flex-wrap items-center justify-start my-2">

                <CartItemButton :product-id="item.cart_item.variation_id"
                                @qtyChanged="(price,discount)=>{item.cart_item.product.showPrice=price;item.cart_item.product.showDiscount=discount} "
                                :price="item.cart_item.product.price"
                                :prices="Array.isArray(item.cart_item.product.prices)?item.cart_item.product.prices :[]"
                                class=" flex min-w-[100%]   xs:min-w-[100%] sm:min-w-[60%] lg:min-w-[50%]
                                hover:cursor-pointer
                "/>
                <div class="flex items-center">
                  <div class="mx-2 ">{{ asPrice(item.cart_item.total_price) }}</div>
                  <div>
                    <TomanIcon class=""/>
                  </div>
                  <div v-if="false && item.cart_item.price_type" class="mx-2 text-sm text-neutral-500 ">{{
                      `( ${__(item.cart_item.price_type)} )`
                    }}
                  </div>

                </div>
              </div>
            </div>
            <div>
              <div class="flex  items-center text-sm  border-b p-2 py-2">
                <div class="text-neutral-600 mx-1">{{ __('cart_total_qty') }}:</div>
                <div class="text-neutral-800 mx-1">{{ shipment.total_items }}</div>
              </div>
              <div v-if="false" class="flex  items-center text-sm  border-b p-2 py-2">
                <div class="text-neutral-600 mx-1">{{ __('total_weight') }}:</div>
                <div class="text-neutral-800 mx-1">{{ shipment.total_weight }}</div>
                <div class="text-neutral-500 mx-1">{{ __('kg') }}</div>
              </div>
              <div class="flex  items-center text-sm  p-2 py-2">
                <div class="text-neutral-600 mx-1">{{ __('cart_total_price') }}:</div>
                <div class="text-neutral-800 mx-1">{{ asPrice(shipment.total_items_price) }}</div>
                <TomanIcon class="w-5 h-5 text-neutral-400"/>
              </div>
              <div class="flex  items-center text-sm  p-2 py-2">
                <div class="text-neutral-600 mx-1">{{ __('discount') }}:</div>
                <div class="text-neutral-800 mx-1">{{ asPrice(shipment.total_discount) }}</div>
                <TomanIcon class="w-5 h-5 text-neutral-400"/>
              </div>
              <div class="flex  items-center text-sm  p-2 py-2">
                <div class="text-neutral-600 mx-1">{{ __('tax') }}:</div>
                <div class="text-neutral-800 mx-1">{{ asPrice(shipment.tax_price) }}</div>
                <TomanIcon class="w-5 h-5 text-neutral-400"/>
              </div>

            </div>
            <!--           shipping_method-->
            <div v-if="page!='cart'    " class="border-t p-2">
              <div class="text-neutral-500">{{ __('shipping_method') }}</div>
              <div v-if="shipment.method.error_message" class="text-red-500 font-bold">
                {{ shipment.method.error_message }}
              </div>
              <div v-else>
                <div>{{ shipment.method.name }}
                  <span v-if="shipment.method.description" class="text-sm">({{ shipment.method.description }})</span>
                </div>
                <div v-if="shipment.method.address" class="text-sm my-2 text-primary-700">{{ __('address') }}: {{
                    shipment.method.address
                  }}
                </div>
                <div class="flex items-center text-sm">
                  <div class="text-neutral-500">{{ __('distance') }} :</div>
                  <div class="mx-2">{{ `${shipment.distance || '?'} ${__('km')}` }}</div>
                </div>
                <div class="flex items-center">
                  <div class="text-neutral-500">{{ __('shipping_price') }} :</div>

                  <div class="mx-2">
                    {{ shipment.method.pay_type == 'online' ? asPrice(shipment.total_shipping_price) : __('local') }}
                  </div>
                  <TomanIcon v-if="shipment.method.pay_type == 'online'" class=""/>
                </div>

                <div v-if="false" class="my-2">

                  <Timestamp v-if="shipment.has_available_shipping && !shipment.visit_checked  " mode="view"
                             :errors="shipment.error_message"
                             :label="__('delivery_time')"
                             @change=" ($e)=>{let params={};params[`timestamp_shipping_${shipment.method.id}`]= $e  ; update( params)}"
                             v-model="shipment.method.timestamps"/>
                </div>
              </div>
              <div v-if=" shipment.has_available_shipping && shipment.items.length>0 && shipment.items[0].allow_visit "
                   class="my-4 ">
                <TextInput
                    @change=" ($e)=>{let params={};params[`visit_repo_${shipment.repo_id}`]= shipment.visit_checked  ; update( params)}"
                    :id="`visit_repo_${shipment.repo_id}`"
                    type="checkbox"
                    :placeholder="__('visit_repo')"
                    classes=" px-0 mx-0 "
                    v-model="shipment.visit_checked"
                    :autocomplete="`visit_repo_${shipment.repo_id}`"

                >
                </TextInput>
              </div>
            </div>
          </div>
          <div class="flex flex-col  items-end justify-center">

            <div class="flex  items-center text-xs px-2 text-neutral-500 gap-1">
              <div class="flex  " v-if="false && cart.prices" v-for="(price,type) in cart.prices">
                <div v-if="price" class="font-bold">{{ asPrice(price) }}</div>
                <div class="" v-if="price">{{ `( ${__(type)} )` }}</div>
              </div>
            </div>
            <hr class="border border-b w-full  ">
            <div class="flex  items-center justify-end font-bold text-sm  p-4 py-2">
              <div class="text-neutral-600 mx-1">{{ __('order_price') }}:</div>
              <div class="text-neutral-800 mx-1">{{ asPrice(cart.total_price) }}</div>
              <TomanIcon class="w-5 h-5 text-neutral-400"/>
            </div>
          </div>

        </div>
      </section>

      <aside class="min-w-[15rem] sticky bg-neutral-100 my-2 md:my-0  p-2 rounded-lg   md:rounded-s-none ">
        <div class="flex flex-col md:my-4  ">
          <div class="flex  items-center text-sm  border-b p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('orders_count') }}:</div>
            <div class="text-neutral-800 mx-1">{{ cart.orders.length }}</div>
          </div>
          <div class="flex  items-center text-sm  border-b p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('cart_total_qty') }}:</div>
            <div class="text-neutral-800 mx-1">{{ cart.total_items }}</div>
          </div>
          <div v-if="false" class="flex  items-center text-sm border-b  p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('total_weight') }}:</div>
            <div class="text-neutral-800 mx-1">{{ cart.total_weight }}</div>
            <div class="text-neutral-500 mx-1">{{ __('kg') }}</div>
          </div>
          <div class="flex  items-center text-sm  p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('total_shipping_price') }}:</div>
            <div class="text-neutral-800 mx-1">{{ asPrice(cart.total_shipping_price) }}</div>
            <TomanIcon class="w-5 h-5 text-neutral-400"/>
          </div>
          <div class="flex  items-center text-sm  p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('cart_total_price') }}:</div>
            <div class="text-neutral-800 mx-1">{{ asPrice(cart.total_items_price) }}</div>
            <TomanIcon class="w-5 h-5 text-neutral-400"/>
          </div>

          <div class="flex  items-center justify-start   text-sm  p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('discount') }}:</div>
            <div class="text-neutral-800 mx-1">{{ asPrice(cart.total_discount) }}</div>
            <TomanIcon class="w-5 h-5 text-neutral-400"/>
          </div>
          <div class="flex  items-center justify-start   text-sm  p-4 py-2">
            <div class="text-neutral-600 mx-1">{{ __('tax') }}:</div>
            <div class="text-neutral-800 mx-1">{{ asPrice(cart.tax_price) }}</div>
            <TomanIcon class="w-5 h-5 text-neutral-400"/>
          </div>
          <div class="flex flex-col items-end justify-center">
            <div class="flex border-b items-center text-xs p-2 text-neutral-500 gap-1">
              <div class="flex gap-2" v-if="false && cart.prices" v-for="(price,type) in cart.prices">
                <div class="font-bold">{{ asPrice(price) }}</div>
                <div>{{ `( ${__(type)} )` }}</div>
              </div>
            </div>

            <div class="flex  items-center justify-start font-bold text-sm  p-4 py-2">
              <div class="text-neutral-600 mx-1">{{ __('total_price') }}:</div>
              <div class="text-neutral-800 mx-1">{{ asPrice(cart.total_price) }}</div>
              <TomanIcon class="w-5 h-5 text-neutral-400"/>
            </div>
          </div>


          <PrimaryButton :class="{'opacity-50 disabled':cart.orders.length==0}"
                         @click="handleNextButtonClick"
                         classes="" class="my-2">
            <span v-if="!loading">   {{
                page == 'shipping' ? __('order_and_payment') : page == 'payment' ? cart.payment_method == 'local' ? __('reg_order') : __('pay') : __('complete_and_add_address')
              }}</span>
            <LoadingIcon v-else class="fill-white w-8 mx-auto" ref="loader" type="line-dot"/>
          </PrimaryButton>
        </div>
      </aside>

    </div>

    <LoadingIcon v-show="loading" ref="loader" type="linear"/>
  </Scaffold>

</template>

<script>
import AddressSelector from "@/Components/AddressSelector.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import TomanIcon from "@/Components/TomanIcon.vue";
import Image from "@/Components/Image.vue";
import Scaffold from "@/Layouts/Scaffold.vue";
import {Head, Link} from '@inertiajs/vue3';
import heroImage from '@/../images/hero.jpg';
import {loadScript} from "vue-plugin-load-script";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {
  EyeIcon,
  MapPinIcon,
  ShoppingBagIcon,
  CheckCircleIcon,
  ChevronDownIcon,
} from "@heroicons/vue/24/outline";
import {
  PencilIcon,
  ArrowTrendingUpIcon,
} from "@heroicons/vue/24/solid";
import SearchInput from "@/Components/SearchInput.vue";
import LocationSelector from "@/Components/LocationSelector.vue";
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Navigation, Pagination, Scrollbar, A11y} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import CartItemButton from "@/Components/CartItemButton.vue";
import {Dropdown, initTE, Modal} from "tw-elements";
import Timestamp from "@/Components/Timestamp.vue";
import {MenuButton, MenuItem, MenuItems, Menu} from "@headlessui/vue";

export default {
  data() {
    return {
      page: 'cart',
      cart: null,
      loading: false,
      modules: [Navigation, Pagination, Scrollbar, A11y],
    }
  },
  props: ['heroText'],
  components: {
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
    Timestamp,
    CartItemButton,
    SearchInput,
    SecondaryButton,
    PrimaryButton,
    Scaffold,
    Head,
    LoadingIcon,
    Image,
    EyeIcon,
    Link,
    PencilIcon,
    Swiper,
    SwiperSlide,
    LocationSelector,
    MapPinIcon,
    ArrowTrendingUpIcon,
    TomanIcon,
    ShoppingBagIcon,
    AddressSelector,
    TextInput,
    CheckCircleIcon,
    ChevronDownIcon,
  },
  // mixins: [Mixin],
  setup(props) {

  }, mounted() {
    // this.setScroll(this.$refs.loader.$el);
    if (route().current('checkout.shipping'))
      this.page = 'shipping';
    else if (route().current('checkout.payment'))
      this.page = 'payment';

    // this.getCart();
    this.update();
    this.emitter.on('updateCart', (cart) => {
      this.cart = cart;

    });

  },
  methods: {
    handleNextButtonClick() {
      if (this.loading) return;
      if (this.page == 'cart') {
        this.update({current: 'checkout.cart', next: 'checkout.shipping'});
      } else if (this.page == 'shipping') {
        this.update({current: 'checkout.shipping', next: 'checkout.payment'});
      } else if (this.page == 'payment') {
        this.update({current: 'checkout.payment', next: 'order.create', cmnd: 'create_order_and_pay'});
      }

    },
    update(params = {}) {
      this.isLoading(true);
      params.payment_method = params.payment_method || (this.cart ? this.cart.payment_method : null);
      params.current = `checkout.${this.page}`;
      this.loading = true;
      window.axios.patch(route('cart.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message && params.length > 0) {
              this.showToast('success', response.data.message);
            }

            if (response.data.cart) {
              this.updateCart(response.data.cart);
              this.cart = response.data.cart;
            }
            if (params.next) {
              if (this.cart.errors.length > 0 && this.page != 'cart')
                this.showToast('danger', this.__('please_correct_errors'));
              else if (response.data.url)
                window.location = response.data.url;
              else
                this.$inertia.visit(route(params.next));
            }
          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {
              if (error.response.data.cart) {
                this.updateCart(error.response.data.cart);
                this.cart = error.response.data.cart;
              }
            }
            this.showToast('danger', this.error);

          })
          .finally(() => {
            // always executed
            this.loading = false;
            this.isLoading(false);
          });
    },
  }

}
</script>
<style type="text/css">.turbo-progress-bar {
  position: fixed;
  display: block;
  top: 0;
  left: 0;
  height: 3px;
  background: #32CD32;
  z-index: 9999;
  transition: width 300ms ease-out,
  opacity 150ms 150ms ease-in;
  transform: translate3d(0, 0, 0);
}
</style>
<!--swiper settings in swiper tag-->
<!--:auto-height="true"-->
<!--:slides-per-view="'auto'"-->
<!--:breakpoints="{-->
<!--0: {-->
<!--slidesPerView: 1,-->
<!--},-->
<!--350: {-->
<!--slidesPerView: 1,-->

<!--},-->
<!--540: {-->
<!--slidesPerView: 2,-->

<!--},-->
<!--768: {-->
<!--slidesPerView: 3,-->

<!--},-->
<!--1100: {-->
<!--slidesPerView: 4,-->

<!--},-->
<!--1200: {-->
<!--slidesPerView: 5,-->

<!--},-->
<!--}"-->