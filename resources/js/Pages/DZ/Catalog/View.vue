<template>
  <Scaffold navbar-theme="dark">
    <template v-slot:header>
      <title v-if="data">{{ data.title }}</title>
      <meta v-if="data" name="description" :content=" data.seo ">

    </template>
    <div
        class="  py-8  shadow-md bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-400 to-primary-500">

    </div>
    <section class=" container  max-w-2xl  mx-auto justify-center pt-24  ">
      <div
          class="w-full   rounded-lg overflow-x-hidden     xs:mx-2 md:mx-4    blur-xs   bg-white   ">
        <div v-if="!data" class="text-center flex flex-col font-bold p-4 text-danger  text-lg">
          <div class="text-gray-900">{{ __('no_result') }}</div>
          <Link :href="$page.props.back_link" class="my-4">{{ __('return') }}</Link>
        </div>
        <!--      main section-->

        <div v-else class="flex flex-col     ">

          <div class="flex-col md:flex-row md:flex  ">

            <div class=" max-w-sm     mx-auto   ">
              <Image
                  :src="data.image_url"

                  classes="object-contain rounded-lg cursor-pointer   "/>

            </div>
            <div class="grow max-w-sm mx-auto">
              <div class="     bg-neutral-50 rounded-lg p-2 text-neutral-700">
                <div class="w-full   my-1   ">
                  <CartItemButton
                      :prices="Array.isArray(data.prices)?data.prices :[]"
                      :key="data.id" class="w-full " :product-id="data.id"/>
                </div>
                <div class="flex items-center justify-between">
                  <div class="text-primary-600 ms-1 font-bold ">{{ data.name_fa }}</div>
                  <div v-if="false" class="text-sm text-neutral-500 mx-2 ">{{ __('grade') + ' ' + data.grade }}</div>

                </div>

                <div class="p-4   w-full flex flex-col items-stretch justify-start items-start items-between">

                  <hr class="border-gray-200  m-2">
                  <div class="bg-neutral-100 flex justify-end items-center p-2 text-sm">
                    <div>{{ data.name_en }}</div>
                  </div>
                  <div class="flex items-center text-sm">

                    <div class="flex items-center text-sm ">
                      <div class="">{{ __('pn') + ' : ' }}</div>
                      <div class="  text-neutral-700 font-bold mx-1 ">{{ data.pn }}</div>

                    </div>
                  </div>
                  <div v-if="data.image_indicator" class="flex items-center text-sm ">
                    <div class="">{{ __('image_indicator') + ' : ' }}</div>
                    <div class="  text-neutral-700 font-bold mx-1 ">{{ data.image_indicator }}</div>

                  </div>
                  <div class="flex items-center justify-end text-neutral-900 font-bold">

                    <div class="flex items-center "
                    >
                      {{ (data.prices || []).map(i => asPrice(i.price)).join(' | ') }}

                    </div>

                    <TomanIcon class="w-4 h-4 mx-2"/>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div v-html="data.description"
               class="   p-2 md:p-4  bg-gray-50 rounded-lg  ">

          </div>


        </div>
      </div>
    </section>


  </Scaffold>
</template>

<script>

import Scaffold from "@/Layouts/Scaffold.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head, Link} from "@inertiajs/vue3";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import Image from "@/Components/Image.vue";
import Article from "@/Components/Article.vue";
import TomanIcon from "@/Components/TomanIcon.vue";
import {ChatBubbleLeftEllipsisIcon} from "@heroicons/vue/24/outline";
import {
  EyeIcon,
  CurrencyDollarIcon,
  UserIcon,
  ArrowTrendingUpIcon,

} from "@heroicons/vue/24/solid";
import CartItemButton from "@/Components/DZ/CartItemButton.vue";

export default {
  data() {
    return {
      html: null,
      timer: 0,
      timerPercent: 0,
      auto_view: this.$page.props.auto_view,
      error: null,
      data: null,
      available_sites: 0,
      hiddenProp: null,
      intervalId: null,
      sites: [],
      loading: false,
      params: {
        page: 0,
        search: null,
        order_by: null,
        dir: null,
      }
    }
  },
  components: {
    CartItemButton,
    Scaffold,
    Image,
    Link,
    EyeIcon,
    CurrencyDollarIcon,
    LoadingIcon,
    PrimaryButton,
    SecondaryButton,
    UserIcon,
    Article,
    ArrowTrendingUpIcon,
    TomanIcon,
    ChatBubbleLeftEllipsisIcon,
  },
  created() {
    // this.isLoading(true);

  },
  mounted() {

    this.data = this.$page.props.data;

    this.increaseView(this.data.id);
  },
  methods: {
    increaseView(id) {
      window.axios.post(route('article.view'), {id: id},);


    },
  },

}

</script>