<template>
  <nav class="shadow-lg   w-full z-[1043] top-0 ">
    <div class="max-w-7xl  mx-auto   px-2 lg:px-4">
      <div class="flex flex-col py-2">
        <div class="flex justify-between">
          <div class="flex space-x-4 grow items-center justify-start">
            <!-- Website Logo -->
            <div>
              <Link :href="route('/')" class="flex items-center py-4 px-2">
                <ApplicationLogo class="w-9 h-9 fill-current text-primary-600"/>
                <span class="font-semibold text-white nav-item text-lg mx-2"
                >{{ __('app_name') }}</span>

              </Link>
            </div>
            <div v-if="!route().current('shop.index')" class="  grow   max-w-md mx-auto ">
              <SearchInput class="hidden md:block " v-model="params.search"
                           @search="search( )"/>
            </div>
          </div>
          <!-- Primary Navbar items -->

          <!-- Secondary Navbar items -->
          <div class="   flex items-center space-x-3   ">
            <CartButton/>
            <UserButton/>
            <!--          <LanguageButton/>-->
          </div>
          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center nav-item ">
            <button class="h-9 w-9   border-2 rounded  mobile-menu-button ">
              <Bars3Icon class=" " className="  "/>
            </button>
          </div>
        </div>
        <div
            class="hidden md:flex items-center grow  justify-start  text-xs  transition-all duration-500">
          <div class="flex items-center">
            <!--            <Link :href="route('/')" class="px-4 nav-item" :class="navClasses('/')">-->
            <!--              {{ __('home') }}-->
            <!--            </Link>-->

            <Link :href="route('shop.index')" class="nav-item" :class="navClasses('shop')">
              {{ __('shop') }}
            </Link>
            <Link :href="route('article.index')" class="nav-item" :class="navClasses('article')">
              {{ __('articles') }}
            </Link>
            <button @click="scrollTo('footer') " class="nav-item " :class="navClasses('page.contact_us')">
              {{ __('contact_us') }}
            </button>
            <!--            <Link :href="route('page.contact_us')" class="nav-item " :class="navClasses('page.contact_us')">-->
            <!--              {{ __('contact_us') }}-->
            <!--            </Link>-->
            <!--            <Link :href="route('page.contact_us')" class="nav-item" :class="navClasses('contact_us')">-->
            <!--              {{ __('contact_us') }}-->
            <!--            </Link>-->
            <!--                        <Link :href="route('exchange.index')" class="nav-item" :class="navClasses('exchange')">-->
            <!--                            {{ __('exchange') }}-->
            <!--                        </Link>-->
          </div>
          <div class="flex items-center">
            <!--            <Link :href="route('page.prices')" class="nav-item" :class="navClasses('prices')">-->
            <!--              {{ __('prices') }}-->
            <!--            </Link>-->
            <!--            <Link :href="route('page.help')" class="nav-item" :class="navClasses('help')">-->
            <!--              {{ __('help') }}-->
            <!--            </Link>-->
            <!--            <Link :href="route('page.contact_us')" class="nav-item" :class="navClasses('contact_us')">-->
            <!--              {{ __('contact_us') }}-->
            <!--            </Link>-->


          </div>

        </div>
      </div>
    </div>
    <!-- mobile menu -->
    <div
        class="      md:hidden     mobile-menu  transform transition-all duration-500  bg-primary-500 px-4 shadow-md  ">
      <div class="mobile-menu-content flex flex-col p-2 ">

        <SearchInput class="  " v-model="params.search" @search="search( )"/>

        <Link :href="route('/')" class="px-4 mobile nav-item m-1" :class="navClasses('/')">
          {{ __('home') }}
        </Link>

        <Link :href="route('shop.index')" class="mobile nav-item m-1" :class="navClasses('shop')">
          {{ __('shop') }}
        </Link>
        <Link :href="route('article.index')" class="mobile nav-item m-1" :class="navClasses('article')">
          {{ __('articles') }}
        </Link>
        <button @click="scrollTo('footer') " class=" mobile nav-item m-1" :class="navClasses('page.contact_us')">
          {{ __('contact_us') }}
        </button>
      </div>
      <!--      <Link :href="route('page.contact_us')" class="nav-ite " :class="navClasses('page.contact_us')">-->
      <!--        {{ __('contact_us') }}-->
      <!--      </Link>-->
    </div>
    <!--        <hr class="border-b border-gray-100 opacity-25 my-0 py-0"/>-->
  </nav>

</template>
<script>

import LanguageButton from "@/Components/LanguageButton.vue";
import CartButton from "@/Components/CartButton.vue";
import UserButton from "@/Components/UserButton.vue";
import {Head, Link} from '@inertiajs/vue3';
import {Bars3Icon, UserIcon} from "@heroicons/vue/24/outline";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import SearchInput from "@/Components/SearchInput.vue";

export default {
  components: {
    SearchInput,
    ApplicationLogo,
    LanguageButton,
    UserButton,
    Bars3Icon,
    UserIcon,
    Link,
    Head,
    CartButton,

  },
  props: ['theme'],
  data() {
    return {params: {}}
  }, mounted() {

    this.params = this.getQueryParams(window.location) ?? {};
    const btn = document.querySelector("button.mobile-menu-button");
    const menu = document.querySelector(".mobile-menu");
    const menuContent = document.querySelector("div.mobile-menu-content");

    function slide() {

      if (menu.style.height === "0px" || menu.style.height === "") {
        // Measure the height of the content
        menuContent.classList.remove('hidden')

        const contentHeight = menuContent.offsetHeight;

        // Set the height dynamically
        menu.style.height = `${contentHeight}px`;

      } else {
        // Collapse the menu
        menu.style.height = "0px";
        menuContent.classList.add('hidden')


      }
    }

    menuContent.classList.add('hidden')

    btn.addEventListener("click", () => {
      slide()

    });

    this.setScrollListener();
  },
  methods: {
    search() {
      window.location = this.route('shop.index', this.params)
    },
    navClasses(item) {
      let base = "py-4 text-white rounded-lg px-2 lg:px-2  font-semibold  transition    hover:bg-primary-400 hover:text-white  duration-300 ";
      if (item && (this.route().current(`${item}.*`) || this.route().current(`${item}`)))
        base = "py-4 active rounded-lg px-2 lg:px-2 text-primary-500  bg-primary-100   font-semibold  transition    hover:bg-primary-400 hover:text-white  duration-300 ";
      return base;
    },
    setScrollListener() {
      var scrollpos = window.scrollY;
      var nav = document.getElementsByTagName("nav")[0];
      var links = document.querySelectorAll(".nav-item");
      var buttons = document.querySelectorAll(".btn");
      if (this.theme == 'light') {
        nav.classList.remove("bg-transparent");
        nav.classList.add("bg-white");
        nav.classList.remove("text-white");
        nav.classList.add("text-primary-500");
        nav.classList.add("shadow-lg");

        for (let el of links) {
          if (el.classList.contains('mobile')) continue;
          el.classList.remove("text-white");
          el.classList.add("text-primary-500");
        }
        for (let el of buttons) {
          el.classList.remove("bg-white");
          el.classList.add("bg-primary-500");
          el.classList.remove("text-primary-500");
          el.classList.add("text-white");
          el.classList.remove("border-primary-500");
          el.classList.add("border-white");
        }
        return;
      } else {

        nav.classList.add("bg-transparent");
        nav.classList.remove("bg-white");
        nav.classList.add("text-white");
        nav.classList.remove("text-primary-500");
        nav.classList.remove("shadow-lg");

        for (let el of links) {
          if (!el.classList.contains("active")) {
            el.classList.add("text-white");
            el.classList.remove("text-primary-500");
          }
        }
        for (let el of buttons) {
          el.classList.remove("bg-white");
          el.classList.add("bg-primary-500");
          el.classList.remove("text-primary-500");
          el.classList.add("text-white");
          el.classList.remove("border-primary-500");
          el.classList.add("border-white");
        }

      }

      document.addEventListener("scroll", function () {
        /*Apply classes for slide in bar*/
        scrollpos = window.scrollY;
        for (let el of links) {
          if (el.classList.contains('mobile')) continue;
          el.classList.remove("text-primary-500");
          el.classList.add("text-white");
        }
        if (scrollpos > 10) {
          nav.classList.remove("bg-transparent");
          nav.classList.add("bg-white");
          nav.classList.remove("text-white");
          nav.classList.add("text-primary-500");
          nav.classList.add("shadow-lg");
          // nav.classList.add("fixed");

          for (let el of links) {
            if (el.classList.contains('mobile')) continue;
            el.classList.remove("text-white");
            el.classList.add("text-primary-500");

          }
          for (let el of buttons) {
            el.classList.add("bg-white");
            el.classList.remove("bg-primary-500");
            el.classList.add("text-primary-500");
            el.classList.remove("text-white");
            el.classList.add("border-primary-500");
            el.classList.remove("border-white");
          }
        } else {
          nav.classList.add("bg-transparent");
          nav.classList.remove("bg-white");
          nav.classList.add("text-white");
          nav.classList.remove("text-primary-500");
          nav.classList.remove("shadow-lg");
          // nav.classList.remove("fixed");

          for (let el of links) {

            if (!el.classList.contains("active")) {
              el.classList.add("text-white");
              el.classList.remove("text-primary-500");
            } else {
              el.classList.add("text-primary-500");
              el.classList.remove("text-white");
            }
          }
          for (let el of buttons) {
            el.classList.remove("bg-white");
            el.classList.add("bg-primary-500");
            el.classList.remove("text-primary-500");
            el.classList.add("text-white");
            el.classList.remove("border-primary-500");
            el.classList.add("border-white");
          }

        }
      });

    }
  }
}
</script>

<style lang="scss">
.nav-item {
  //color: white;
}
</style>
