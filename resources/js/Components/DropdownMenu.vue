<template>
  <div v-if="false">
    <div class="relative inline-block text-start">
      <div>
        <button type="button"
                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50"
                :id="forId" aria-expanded="false" aria-haspopup="true">
          Options
          <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
               data-slot="icon">
            <path fill-rule="evenodd"
                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd"/>
          </svg>
        </button>
      </div>

      <!--
        Dropdown menu, show/hide based on menu state.

        Entering: "transition ease-out duration-100"
          From: "transform opacity-0 scale-95"
          To: "transform opacity-100 scale-100"
        Leaving: "transition ease-in duration-75"
          From: "transform opacity-100 scale-100"
          To: "transform opacity-0 scale-95"
      -->
      <div
          class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
          role="menu" aria-orientation="vertical" :aria-labelledby="forId" tabindex="-1">
        <div class="py-1" role="none">
          <!-- Active: "bg-gray-100 text-gray-900 outline-hidden", Not Active: "text-gray-700" -->
          <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-0">Account
            settings</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-1">Support</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-2">License</a>
          <form method="POST" action="#" role="none">
            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700" role="menuitem"
                    tabindex="-1" id="menu-item-3">Sign out
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
  <div v-if="false" class="relative" data-te-dropdown-ref>
    <slot name="toggle"/>
    <ul
        class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-base shadow-lg data-[te-dropdown-show]:block dark:bg-surface-dark"
        :aria-labelledby="forId"
        data-te-dropdown-menu-ref>
      <template v-for="(d,idx) in data.children  ">
        <li>

          <template v-if="d.children?.length>0">
            <DropdownMenu :data="d " :forId="`dropdownMenuButton${d.id}`" data-te-dropdown-position="dropstart">

              <template v-slot:toggle>

                <a href="#"
                   type="button"
                   :class="{'!bg-primary-500 ':false}"
                   class="p-3 focus:outline-none flex relative bg-white text-neutral-600 hover:bg-primary-400 duration-100  hover:text-white   1max-w-[3.5rem] flex-col      items-center hover:cursor-pointer     "
                   :id="`dropdownMenuButton${d.id}`"
                   data-te-dropdown-toggle-ref
                   data-te-dropdown-animation="true"
                   aria-expanded="false"
                   data-te-ripple-init
                   data-te-ripple-color="light"
                >
                  <div class="flex items-center ">
                    <div class="me-3 w-2 [&>svg]:h-5 [&>svg]:w-5">
                      <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor">
                        <path
                            fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd"/>
                      </svg>
                    </div>

                    <div
                        class="text-xs text-center  ">{{ d.name }}
                    </div>
                  </div>

                </a>

              </template>

            </DropdownMenu>
          </template>
          <template v-else>
            <div
                @click=" $emit('clicked', data.id  )"
                class="block cursor-pointer hover:bg-primary-400 hover:text-white w-full whitespace-nowrap bg-white px-4 py-2 text-sm font-normal text-neutral-700   focus:bg-zinc-200/60 focus:outline-none active:bg-zinc-200/60 active:no-underline dark:bg-surface-dark dark:text-white dark:hover:bg-neutral-800/25 dark:focus:bg-neutral-800/25 dark:active:bg-neutral-800/25"
                data-te-dropdown-item-ref
            >{{ d.name }}
            </div
            >
          </template>
        </li>
      </template>


    </ul>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps(['data', 'forId']);


defineEmits(['clicked',]);

const input = ref(null);

onMounted(() => {

});
</script>

<style scoped>

</style>