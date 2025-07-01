<template>
  <Scaffold navbar-theme="light">
    <template v-slot:header>
      <title>{{__('shop')}}</title>

    </template>
    <div
        class="    shadow-md bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-400 to-primary-500">

    </div>

    <section
        class="flex   flex-wrap gap-2 w-full bg-gray-100 rounded-b-2xl shadow-md p-2  px-2 lg:px-4 items-center z-[-10]">
      <!--      <LocationSelector-->
      <!--          @change="  params.province_id=$event.province_id;params.county_id=$event.county_id;params.district_id=$event.district_id; getData(0);"/>-->

      <SearchInput v-if="false" class="shrink max-w-xs " v-model="params.search" @search="getData(0)"/>

      <Selector v-if="false" ref="gradeSelector" v-model="params.grade"
                :data="$page.props.grades.map(e=>{return{id:e,name:`${__('grade')} ${e}`}})"
                @update:model-value="getData(0); "
                :placeholder="__('grade')"
                class="shrink  "
                :id="`grade`">

      </Selector>
      <swiper v-if="false"
              :modules="[modules[0],  modules[2],modules[3],]"
              :slides-per-view="'auto'"
              :space-between="4"
              :pagination="{ clickable: true }"
              :scrollbar="{ draggable: true ,  }"
              @swiper=""
              @slideChange=""
              class="w-full p-3   !overflow-y-visible">
        <swiper-slide @click2=" toggleCategory(p.id  );getData(0)"
                      class=" !w-auto"

                      v-for="p in $page.props.categories ">


          <DropdownMenu :data="p" :forId="`dropdownMenuButton${p.id}`">

            <template v-slot:toggle>

              <a href="#"
                 type="button"
                 :class="{'!bg-primary-500 ':params.category_ids.filter((e)=> p.id==e).length>0}"
                 class="p-3 focus:outline-none flex relative bg-white text-neutral-600 hover:bg-primary-400 duration-100  hover:text-white   1max-w-[3.5rem] flex-col rounded  mb-4  items-center hover:cursor-pointer  rounded-lg shadow  hover:scale-[105%] "
                 :id="`dropdownMenuButton${p.id}`"
                 data-te-dropdown-toggle-ref
                 aria-expanded="false"
                 data-te-ripple-init
                 data-te-ripple-color="light"
              >
                <div class="flex items-center ">
                  <div :class="{'opacity-0 ![&>svg]:w-0 me-0':p.children?.length==0}" class="me-3 w-2  h-5 [&>svg]:w-5">
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
                      class="text-xs text-center  ">{{ replaceAll(p.name, ' ', "‌") }}
                  </div>
                </div>

              </a>

            </template>

          </DropdownMenu>


        </swiper-slide>
      </swiper>
    </section>


    <section v-if="products.length>0" class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
      <form class="mt-4 border-t border-gray-200">
        <h3 class="sr-only">Categories</h3>
        <ul role="list" class="px-2 py-3 font-medium text-gray-900">
          <li>
            <a href="#" class="block px-2 py-3">Totes</a>
          </li>
          <li>
            <a href="#" class="block px-2 py-3">Backpacks</a>
          </li>
          <li>
            <a href="#" class="block px-2 py-3">Travel Bags</a>
          </li>
          <li>
            <a href="#" class="block px-2 py-3">Hip Bags</a>
          </li>
          <li>
            <a href="#" class="block px-2 py-3">Laptop Sleeves</a>
          </li>
        </ul>

        <div class="border-t border-gray-200 px-4 py-6">
          <h3 class="-mx-2 -my-3 flow-root">
            <!-- Expand/collapse section button -->
            <button type="button"
                    class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500"
                    aria-controls="filter-section-mobile-color" aria-expanded="false">
              <span class="font-medium text-gray-900">Color</span>
              <span class="ml-6 flex items-center">
                    <!-- Expand icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path
                          d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                <!-- Collapse icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd"
                            d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                            clip-rule="evenodd"/>
                    </svg>
                  </span>
            </button>
          </h3>
          <!-- Filter section, show/hide based on section state. -->
          <div class="pt-6" id="filter-section-mobile-color">
            <div class="space-y-6">
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-0" name="color[]" value="white" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-0" class="min-w-0 flex-1 text-gray-500">White</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-1" name="color[]" value="beige" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-1" class="min-w-0 flex-1 text-gray-500">Beige</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-2" name="color[]" value="blue" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-2" class="min-w-0 flex-1 text-gray-500">Blue</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-3" name="color[]" value="brown" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-3" class="min-w-0 flex-1 text-gray-500">Brown</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-4" name="color[]" value="green" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-4" class="min-w-0 flex-1 text-gray-500">Green</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-color-5" name="color[]" value="purple" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-color-5" class="min-w-0 flex-1 text-gray-500">Purple</label>
              </div>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-6">
          <h3 class="-mx-2 -my-3 flow-root">
            <!-- Expand/collapse section button -->
            <button type="button"
                    class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500"
                    aria-controls="filter-section-mobile-category" aria-expanded="false">
              <span class="font-medium text-gray-900">Category</span>
              <span class="ml-6 flex items-center">
                    <!-- Expand icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path
                          d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                <!-- Collapse icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd"
                            d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                            clip-rule="evenodd"/>
                    </svg>
                  </span>
            </button>
          </h3>
          <!-- Filter section, show/hide based on section state. -->
          <div class="pt-6" id="filter-section-mobile-category">
            <div class="space-y-6">
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-category-0" name="category[]" value="new-arrivals" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-category-0" class="min-w-0 flex-1 text-gray-500">New Arrivals</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-category-1" name="category[]" value="sale" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-category-1" class="min-w-0 flex-1 text-gray-500">Sale</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-category-2" name="category[]" value="travel" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-category-2" class="min-w-0 flex-1 text-gray-500">Travel</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-category-3" name="category[]" value="organization" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-category-3" class="min-w-0 flex-1 text-gray-500">Organization</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-category-4" name="category[]" value="accessories" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-category-4" class="min-w-0 flex-1 text-gray-500">Accessories</label>
              </div>
            </div>
          </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-6">
          <h3 class="-mx-2 -my-3 flow-root">
            <!-- Expand/collapse section button -->
            <button type="button"
                    class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500"
                    aria-controls="filter-section-mobile-size" aria-expanded="false">
              <span class="font-medium text-gray-900">Size</span>
              <span class="ml-6 flex items-center">
                    <!-- Expand icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path
                          d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                <!-- Collapse icon, show/hide based on section open state. -->
                    <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd"
                            d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z"
                            clip-rule="evenodd"/>
                    </svg>
                  </span>
            </button>
          </h3>
          <!-- Filter section, show/hide based on section state. -->
          <div class="pt-6" id="filter-section-mobile-size">
            <div class="space-y-6">
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-0" name="size[]" value="2l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-0" class="min-w-0 flex-1 text-gray-500">2L</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-1" name="size[]" value="6l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-1" class="min-w-0 flex-1 text-gray-500">6L</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-2" name="size[]" value="12l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-2" class="min-w-0 flex-1 text-gray-500">12L</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-3" name="size[]" value="18l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-3" class="min-w-0 flex-1 text-gray-500">18L</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-4" name="size[]" value="20l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-4" class="min-w-0 flex-1 text-gray-500">20L</label>
              </div>
              <div class="flex gap-3">
                <div class="flex h-5 shrink-0 items-center">
                  <div class="group grid size-4 grid-cols-1">
                    <input id="filter-mobile-size-5" name="size[]" value="40l" type="checkbox"
                           class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-gray-950/25"
                        viewBox="0 0 14 14" fill="none">
                      <path class="opacity-0 group-has-checked:opacity-100" d="M3 8L6 11L11 3.5" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                      <path class="opacity-0 group-has-indeterminate:opacity-100" d="M3 7H11" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                <label for="filter-mobile-size-5" class="min-w-0 flex-1 text-gray-500">40L</label>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="lg:col-span-3">
        <div
            class="  mt-6   gap-y-3 gap-x-2 grid   sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 ">
          <div class="bg-white   shadow-md rounded-lg    "
               v-for="(p,idx) in products">
            <div :id="p.id"
                 class="   flex flex-row sm:flex-col h-full    hover:scale-[101%] duration-300">
              <div class="flex flex-col h-full justify-between p-3 w-full ">

                <Link :href=" route( 'variation.view',{id:p.id,name:p.name})">
                  <div class="flex  sm:flex-col  ">
                    <div class="md:mx-auto sm:h-64 sm:w-full  h-24    w-32 shadow-md  ">
                      <!--                <Image :data-lity="route('storage.variations')+`/${p.id}/thumb.jpg`"-->
                      <!--                       classes="object-cover  h-full w-full  rounded-t-lg rounded-b   "-->
                      <!--                       :src="route('storage.variations')+`/${p.id}/thumb.jpg`"></Image> -->
                      <Image classes="object-contain  h-full w-full  rounded-t-lg rounded-b  "
                             :src="route('storage.products')+`/${p.product_id}.jpg`" disabled="true"


                      ></Image>
                    </div>
                    <div v-if="false" class="flex my-1 items-center justify-start text-xs text-gray-400">
                      <div class="  rounded p-1 px-2  "> {{ toRelativeTime(p.updated_at) }}</div>
                    </div>
                    <div class="flex flex-col   p-2 w-full ">
                      <div class="flex items-center justify-between">
                        <div class="text-primary-600 ms-1 text-sm ">{{ p.name }}</div>
                        <!--                <div class="text-sm text-neutral-500 mx-2 ">{{ __('grade') + ' ' + p.grade }}</div>-->

                      </div>
                      <hr class="border-gray-200  m-2">
                      <div class="text-neutral-500 text-sm">{{ p.repo_name }}</div>
                      <div class="flex items-center text-sm">
                        <div>{{ __('in_stock') + ` : ${parseFloat(p.in_shop)}` }}</div>
                        <div class="text-sm text-neutral-500 mx-2" v-if="getPack(p.pack_id)">{{
                            ` ${getPack(p.pack_id)} `
                          }}
                        </div>

                      </div>
                    </div>
                  </div>
                  <!--            <div class="hidden sm:flex min-w-[36%] my-1  mx-auto">-->
                  <!--              <CartItemButton :key="p.id" class="w-full " :product-id="p.id"/>-->
                  <!--            </div>-->

                  <div
                      class="     flex flex-col items-stretch justify-end   ">


                    <!--              <div v-if="p.unit != 'qty'" class="flex items-center text-sm">-->
                    <!--                <div>{{ (__('weight')) + ` : ${parseFloat(p.weight)}` }}</div>-->
                    <!--                <div class="text-sm text-neutral-500 mx-2">{{-->
                    <!--                    p.weight > 0 && p.weight < 1 ? __('gr') : __('kg')-->
                    <!--                  }}-->
                    <!--                </div>-->

                    <!--              </div>-->
                    <div v-if="false &&( !p.prices || p.prices.length==0)"
                         class="flex items-center justify-end ">
                      <div class="flex items-center "
                           :class="{'line-through text-neutral-500':$page.props.is_auction && p.in_auction}">
                        {{ asPrice(Math.round(p.price)) }}

                        <svg v-if="$page.props.is_auction && p.in_auction" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 14 14"
                             class="fill-gray-500 h-5 w-5">
                          <path fill-rule="evenodd"
                                d="M3.057 1.742L3.821 1l.78.75-.776.741-.768-.749zm3.23 2.48c0 .622-.16 1.111-.478 1.467-.201.221-.462.39-.783.505a3.251 3.251 0 01-1.083.163h-.555c-.421 0-.801-.074-1.139-.223a2.045 2.045 0 01-.9-.738A2.238 2.238 0 011 4.148c0-.059.001-.117.004-.176.03-.55.204-1.158.525-1.827l1.095.484c-.257.532-.397 1-.419 1.403-.002.04-.004.08-.004.12 0 .252.055.458.166.618a.887.887 0 00.5.354c.085.028.178.048.278.06.079.01.16.014.243.014h.555c.458 0 .769-.081.933-.244.14-.139.21-.383.21-.731V2.02h1.2v2.202zm5.433 3.184l-.72-.7.709-.706.735.707-.724.7zm-2.856.308c.542 0 .973.19 1.293.569.297.346.445.777.445 1.293v.364h.18v-.004h.41c.221 0 .377-.028.467-.084.093-.055.14-.14.14-.258v-.069c.004-.243.017-1.044 0-1.115L13 8.05v1.574a1.4 1.4 0 01-.287.863c-.306.405-.804.607-1.495.607h-.627c-.061.733-.434 1.257-1.117 1.573-.267.122-.58.21-.937.265a5.845 5.845 0 01-.914.067v-1.159c.612 0 1.072-.082 1.38-.247.25-.132.376-.298.376-.499h-.515c-.436 0-.807-.113-1.113-.339-.367-.273-.55-.667-.55-1.18 0-.488.122-.901.367-1.24.296-.415.728-.622 1.296-.622zm.533 2.226v-.364c0-.217-.048-.389-.143-.516a.464.464 0 00-.39-.187.478.478 0 00-.396.187.705.705 0 00-.136.449.65.65 0 00.003.067c.008.125.066.22.177.283.093.054.21.08.352.08h.533zM9.5 6.707l.72.7.724-.7L10.209 6l-.709.707zm-6.694 4.888h.03c.433-.01.745-.106.937-.29.024.012.065.035.12.068l.074.039.081.042c.135.073.261.133.379.18.345.146.67.22.977.22a1.216 1.216 0 00.87-.34c.3-.285.449-.714.449-1.286a2.19 2.19 0 00-.335-1.145c-.299-.457-.732-.685-1.3-.685-.502 0-.916.192-1.242.575-.113.132-.21.284-.294.456-.032.062-.06.125-.084.191a.504.504 0 00-.03.078 1.67 1.67 0 00-.022.06c-.103.309-.171.485-.205.53-.072.09-.214.14-.427.147-.123-.005-.209-.03-.256-.076-.057-.054-.085-.153-.085-.297V7l-1.201-.5v3.562c0 .261.048.496.143.703.071.158.168.296.29.413.123.118.266.211.43.28.198.084.42.13.665.136v.001h.036zm2.752-1.014a.778.778 0 00.044-.353.868.868 0 00-.165-.47c-.1-.134-.217-.201-.35-.201-.18 0-.33.103-.447.31-.042.071-.08.158-.114.262a2.434 2.434 0 00-.04.12l-.015.053-.015.046c.142.118.323.216.544.293.18.062.325.092.433.092.044 0 .086-.05.125-.152z"
                                clip-rule="evenodd"></path>
                        </svg>


                      </div>
                      <div v-if="$page.props.is_auction &&  p.in_auction==true" class="flex items-center ">
                        <ArrowTrendingUpIcon class="  rotate-180 text-neutral-500 mx-2"/>
                        <span>  {{ asPrice(Math.round(p.auction_price)) }}</span>

                      </div>
                      <TomanIcon class="w-4 h-4 mx-2"/>

                    </div>
                    <div class="flex   w-full   justify-end items-center font-bold">
                      <div class="text-xs  ">{{
                          (Array.isArray(p.prices) ? p.prices : []).map(i => asPrice(i.price)).join(' | ')
                        }}
                      </div>
                      <TomanIcon v-if="p.prices" class="w-4 h-4 mx-2"/>
                      <div v-if="false" class="flex flex-col items-end  ">
                        <div v-for="(pr,idx) in p.prices" class="">
                          <div class="flex   text-xs justify-between gap-2 items-center">

                            <div>{{ `${pr.from} - ${pr.to}` }}</div>
                            <div>{{ `( ${__(pr.type)} )` }}</div>
                            <div class="flex items-center">
                              <div>{{ asPrice(Math.round(pr.price)) }}</div>
                              <TomanIcon class="w-4 h-4 mx-2"/>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                  </div>
                </Link>
                <div @click.self class="flex    min-w-[100%]    ">
                  <CartItemButton :key="p.id" class="w-full " :prices="Array.isArray(p.prices)?p.prices :[]"
                                  :product-id="p.id"/>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <section v-else-if="!loading   "
             class="font-bold text-rose-500  mt-8 justify-center  flex flex-col items-center   ">
      <div>
        {{ __('no_product_in_selected_city') }}
      </div>
    </section>
    <div ref="loader">
      <LoadingIcon v-show="loading" type="linear"/>
    </div>

  </Scaffold>

</template>

<script>
import LoadingIcon from "@/Components/LoadingIcon.vue";
import TomanIcon from "@/Components/TomanIcon.vue";
import Image from "@/Components/Image.vue";
import Scaffold from "@/Layouts/Scaffold.vue";
import {Head, Link} from '@inertiajs/vue3';
import heroImage from '@/../images/hero.jpg';
import {loadScript} from "vue-plugin-load-script";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {EyeIcon, MapPinIcon} from "@heroicons/vue/24/outline";
import {PencilIcon, ArrowTrendingUpIcon} from "@heroicons/vue/24/solid";
import SearchInput from "@/Components/SearchInput.vue";
import LocationSelector from "@/Components/LocationSelector.vue";
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Navigation, Pagination, Scrollbar, A11y} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import CartItemButton from "@/Components/CartItemButton.vue";
import Selector from "@/Components/Selector.vue";
import {Dropdown, initTE, Modal} from "tw-elements";
import DropdownMenu from "@/Components/DropdownMenu.vue";

export default {
  data() {
    return {
      products: [],
      categories: [],
      heroImage,
      loading: false,
      total: 0,
      params: {
        page: 0,
        search: null,
        grade: null,
        products: [],
        order_by: null,
        dir: null,
        category_ids: [],
        province_id: null,
        city_id: null,
        // province_id: this.getUserProvinceId(),
        // city_id: this.getUserCityId(),
      },
      modules: [Navigation, Pagination, Scrollbar, A11y],
    }
  },
  props: ['heroText'],
  components: {
    DropdownMenu,
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
    Selector,
  },
  // mixins: [Mixin],
  setup(props) {

  }, mounted() {
    this.setScroll(this.$refs.loader);
    this.params = Object.assign({}, this.params, this.getQueryParams(window.location) ?? {});

    this.getData();
  },
  methods: {
    toggleCategory(item) {

      //find category

      let i = null;
      for (let idx in this.params?.category_ids ?? []) {
        if (this.params.category_ids[idx] == item) {
          i = idx;
          break;
        }
      }
      if (i != null)
        this.params?.category_ids?.splice(i, 1);
      else
        this.params?.category_ids?.push(item);


    },
    getData(page) {

      if (page == 0) {
        this.params.page = 1;
        this.products = [];
      }

      if (this.total > 0 && this.total <= this.products.length) return;
      this.loading = true;

      window.axios.get(route('variation.search'), {
        params: this.params
      })
          .then((response) => {
            // this.data = this.data.concat(response.data.data);
            this.total = response.data.total;
            this.params.page = response.data.current_page + 1;
            // this.products = response.data.data;
            this.products = this.products.concat(response.data.data);
            // console.log(response.data);
          })
          .catch((error) => {
            this.error = this.getErrors(error);

            this.showToast('danger', this.error)
          })
          .finally(() => {
            // always executed
            this.loading = false;
          });
    },
    setScroll(el) {
      window.onscroll = () => {
//                    const {top, bottom, height} = this.loader.getBoundingClientRect();

        let top_of_element = el.offsetTop;
        let bottom_of_element = el.offsetTop + el.offsetHeight;
        let bottom_of_screen = window.pageYOffset + window.innerHeight;
        let top_of_screen = window.pageYOffset;

        if ((bottom_of_screen + 300 > top_of_element) && (top_of_screen < bottom_of_element + 200) && !this.loading) {
          this.getData();
          // scrolled = true;
//                        console.log('visible')
          // the element is visible, do something
        } else {
//                        console.log('invisible')
          // the element is not visible, do something else
        }
      };
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

.swiper-scrollbar {
  position: relative !important;
  z-index: 0 !important /* Optional spacing between swiper and scrollbar */
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