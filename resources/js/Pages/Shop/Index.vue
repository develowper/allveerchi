<template>
  <Scaffold navbar-theme="light">
    <template v-slot:header>
      <title>{{__('shop')}}</title>

    </template>
    <div
        class="    shadow-md bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-400 to-primary-500">

    </div>

    <section v-if="false"
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

    <div class="bg-white">
      <div>
        <!-- Mobile filter dialog -->
        <TransitionRoot as="template" :show="mobileFiltersOpen">
          <Dialog class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0"
                             enter-to="opacity-100" leave="transition-opacity ease-linear duration-300"
                             leave-from="opacity-100" leave-to="opacity-0">
              <div class="fixed inset-0 bg-black/25"/>
            </TransitionChild>

            <div class="fixed inset-0 z-40 flex">
              <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                               enter-from="translate-x-full" enter-to="translate-x-0"
                               leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                               leave-to="translate-x-full">
                <DialogPanel :dir="dir()"
                             class="relative ml-auto flex w-64     flex-col overflow-y-auto bg-white pt-4 pb-6 shadow-lg">
                  <div class="flex items-center justify-between px-4">

                    <h2 class="text-lg font-medium text-neutral-600">{{ __('filters') }}</h2>

                    <button type="button"
                            class="relative border border-neutral-300 text-neutral-500 hover:border-neutral-400 hover:text-neutral-900 flex size-10 items-center justify-center rounded-md bg-white p-2   hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:outline-hidden"
                            @click="mobileFiltersOpen = false">
                      <span class="absolute -inset-0.5"/>
                      <span class="sr-only">Close menu</span>
                      <XMarkIcon class="size-5 h-5" aria-hidden="true"/>
                    </button>
                  </div>

                  <!-- Filters -->
                  <form class="mt-4 border-t border-gray-200">
                    <h3 class="sr-only">Categories</h3>
                    <ul role="list" class="px-2 py-3  space-y-1 font-medium text-gray-900">

                      <li v-if="$page.props.brands?.length>0">
                        <Disclosure
                            as="div" key="brands-disclosure "
                            class="  " v-slot="{ open }">
                          <h3 class=" flow-root  text-sm     ">
                            <DisclosureButton
                                :class="[open?'rounded-t-lg':'rounded-lg']"
                                class="flex w-full   hover:bg-gray-200  border   items-center justify-between   px-4 py-5 text-gray-400 hover:text-gray-500">
                              <span class="font-medium grow text-start text-gray-900">{{ __('brands') }}</span>
                              <span class="ml-6 flex items-center">
                          <PlusIcon v-if="!open  " class="size-5 h-5" aria-hidden="true"/>
                          <MinusIcon v-else class="size-5 h-5"
                                     aria-hidden="true"/>
                        </span>
                            </DisclosureButton>
                          </h3>
                          <Transition
                              enter="transition duration-100 ease-out"
                              enterFrom="transform scale-95 opacity-0"
                              enterTo="transform scale-100 opacity-100"
                              leave="transition duration-75 ease-out"
                              leaveFrom="transform scale-100 opacity-100"
                              leaveTo="transform scale-95 opacity-0"
                          >
                            <DisclosurePanel class="pt-1 ps-4 bg-gray-50 rounded-b-lg">
                              <div class=" ">
                                <div v-for="(brand, idx) in $page.props.brands " :key="brand.id" class="   ">
                                  <div @click="toggleBrand(brand)"
                                       class="hover:bg-gray-100  cursor-pointer py-2 px-1">
                                    <input @click.stop.prevent tabindex="-1"
                                           :id="`filter-mobile-brand-${brand.id}`"
                                           :checked="params.brand_ids?.includes(brand.id)" type="checkbox"
                                           class="col-start-1 row-start-1 pointer-events-none appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                                    <label @click.prevent
                                           :for="`filter-mobile-category-${brand.id}`"
                                           class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                                        brand.name
                                      }}</label>
                                  </div>

                                </div>
                              </div>
                            </DisclosurePanel>
                          </Transition>
                        </Disclosure>
                      </li>

                      <li v-for="(category,idx) in $page.props.categories"
                          :key="`lic-${category.id}`">
                        <template v-if="category.children?.length>0">
                          <Disclosure
                              as="div" :key="`cat-${category.id}`"
                              class="  " v-slot="{ open }">
                            <h3 class=" flow-root text-sm  bg-white ">
                              <DisclosureButton
                                  :class="[open?'rounded-t-lg':'rounded-lg']"
                                  class="flex w-full border hover:bg-gray-200  items-center justify-between   px-4 py-5 text-gray-400 hover:text-gray-500">
                                <span class="font-medium grow text-start text-gray-900">{{ category.name }}</span>
                                <span class="ml-6 flex items-center">
                          <PlusIcon v-if="!open && category.children?.length>0" class="size-5 h-5" aria-hidden="true"/>
                          <MinusIcon v-else-if="open   && category.children?.length>0" class="size-5 h-5"
                                     aria-hidden="true"/>
                        </span>
                              </DisclosureButton>
                            </h3>
                            <Transition
                                enter="transition duration-100 ease-out"
                                enterFrom="transform scale-95 opacity-0"
                                enterTo="transform scale-100 opacity-100"
                                leave="transition duration-75 ease-out"
                                leaveFrom="transform scale-100 opacity-100"
                                leaveTo="transform scale-95 opacity-0"
                            >
                              <DisclosurePanel class="pt-1 ps-4 bg-gray-50 rounded-b-lg">
                                <div class=" ">
                                  <div v-for="(child, idx) in category.children??[]" :key="child.id" class="   ">
                                    <div @click="toggleCategory(child)"
                                         class="hover:bg-gray-100 cursor-pointer py-2 px-1">
                                      <input @click.stop.prevent tabindex="-1"
                                             :id="`filter-mobile-category-${child.id}`"
                                             :checked="params.category_ids?.includes(child.id)" type="checkbox"
                                             class="col-start-1 row-start-1 pointer-events-none appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                                      <label @click.prevent
                                             :for="`filter-mobile-category-${child.id}`"
                                             class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                                          child.name
                                        }}</label>
                                    </div>

                                  </div>
                                </div>
                              </DisclosurePanel>
                            </Transition>
                          </Disclosure>
                        </template>
                        <template v-else>
                          <div @click="toggleCategory(category)"
                               :class="{'border-t border-gray-200':idx>0}"
                               class="hover:bg-gray-100   cursor-pointer py-3 px-1">
                            <input @click.stop.prevent tabindex="-1" :id="`filter-mobile-category-${category.id}`"
                                   :checked="params.category_ids?.includes(category.id)" type="checkbox"
                                   class="col-start-1 pointer-events-none row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                            <label @click.prevent :for="`filter-mobile-category-${category.id}`"
                                   class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                                category.name
                              }}</label>
                          </div>
                        </template>

                      </li>
                    </ul>

                  </form>
                </DialogPanel>
              </TransitionChild>
            </div>
          </Dialog>
        </TransitionRoot>

        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex items-baseline justify-between border-b border-gray-200   pb-2">
            <h1 class="text-xl font-bold tracking-tight text-primary-500">{{ __('products') }}</h1>

            <div class="flex items-center sticky">
              <SearchInput class=" grow max-w-lg mx-2" v-model="params.search" @search="getData(0)"/>

              <Menu as="div" class="relative inline-block text-start">
                <div>
                  <MenuButton
                      class="group inline-flex items-center justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                    <div>{{
                        sortOptions.find(i => i.params.dir == params.dir && i.params.order_by == params.order_by)?.name ?? __('sort')
                      }}
                    </div>
                    <ChevronDownIcon class="mx-1  h-5 text-gray-400 group-hover:text-gray-500"
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
                      class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black/5 focus:outline-hidden">
                    <div class="py-1">
                      <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                        <div @click="Object.assign(params, option.params);getData(0 )"
                             class="cursor-pointer hover:bg-primary-400 hover:text-white"
                             :class="[ option.params.dir == params.dir && option.params.order_by == params.order_by  ? 'font-medium text-white bg-primary-500' : 'text-gray-500', active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm']">
                          {{
                            option.name
                          }}
                        </div>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>

              <button v-if="false" type="button" class="flex   p-2 text-neutral-500 hover:text-neutral-400 lg:hidden">
                <span class="sr-only">View grid</span>
                <Squares2X2Icon class="size-5 h-5" aria-hidden="true"/>
              </button>
              <button type="button"
                      class=" flex   p-2 text-neutral-500 hover:text-neutral-400   lg:hidden"
                      @click="mobileFiltersOpen = true">
                <span class="sr-only">Filters</span>
                <FunnelIcon class="size-5 h-5" aria-hidden="true"/>
              </button>
            </div>
          </div>

          <section aria-labelledby="products-heading" class="pt-6 pb-24">
            <h2 id="products-heading" class="sr-only">Products</h2>

            <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
              <!-- Filters -->
              <aside :dir="dir()"
                     class="hidden lg:block relative   flex  w-64     flex-col  bg-white pt-4   shadow-lg">
                <div class="flex items-center justify-between px-4">

                  <h2 class="text-lg font-medium text-neutral-600">{{ __('filters') }}</h2>


                </div>

                <!-- Filters -->
                <form class="mt-4 border-t border-gray-200">
                  <h3 class="sr-only">Brands</h3>
                  <ul role="list" class="px-2 py-3 space-y-1  font-medium text-gray-900">

                    <li v-if="$page.props.brands?.length>0">
                      <Disclosure
                          as="div" key="brands-disclosure "

                          class="  " v-slot="{ open }">
                        <h3 class=" flow-root  text-sm    ">
                          <DisclosureButton
                              :class="[open?'rounded-t-lg':'rounded-lg']"
                              class="flex w-full   hover:bg-gray-200   border   items-center justify-between   px-4 py-5 text-gray-400 hover:text-gray-500">
                            <span class="font-medium grow text-start text-gray-900">{{ __('brands') }}</span>
                            <span class="ml-6 flex items-center">
                          <PlusIcon v-if="!open  " class="size-5 h-5" aria-hidden="true"/>
                          <MinusIcon v-else class="size-5 h-5"
                                     aria-hidden="true"/>
                        </span>
                          </DisclosureButton>
                        </h3>
                        <Transition
                            enter="transition duration-100 ease-out"
                            enterFrom="transform scale-95 opacity-0"
                            enterTo="transform scale-100 opacity-100"
                            leave="transition duration-75 ease-out"
                            leaveFrom="transform scale-100 opacity-100"
                            leaveTo="transform scale-95 opacity-0"
                        >
                          <DisclosurePanel class="pt-1 ps-4 bg-gray-50 rounded-b-lg">
                            <div class=" ">
                              <div v-for="(brand, idx) in $page.props.brands " :key="brand.id" class="   ">
                                <div @click="toggleBrand(brand)"
                                     class="hover:bg-gray-100  cursor-pointer py-2 px-1">
                                  <input @click.stop.prevent tabindex="-1"
                                         :id="`filter-mobile-brand-${brand.id}`"
                                         :checked="params.brand_ids?.includes(brand.id)" type="checkbox"
                                         class="col-start-1 row-start-1 pointer-events-none appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                                  <label @click.prevent
                                         :for="`filter-mobile-category-${brand.id}`"
                                         class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                                      brand.name
                                    }}</label>
                                </div>

                              </div>
                            </div>
                          </DisclosurePanel>
                        </Transition>
                      </Disclosure>
                    </li>

                    <li v-for="(category,idx) in $page.props.categories" :key="`lic-${category.id}`">
                      <template v-if="category.children?.length>0">
                        <Disclosure
                            as="div" :key="`cat-${category.id}`"
                            class="  " v-slot="{ open }">
                          <h3 class=" flow-root text-sm  bg-white ">
                            <DisclosureButton
                                :class="[open?'rounded-t-lg':'rounded-lg']"
                                class="flex w-full border hover:bg-gray-200 items-center justify-between   px-4 py-5 text-gray-400 hover:text-gray-500">
                              <span class="font-medium grow text-start text-gray-900">{{ category.name }}</span>
                              <span class="ml-6 flex items-center">
                          <PlusIcon v-if="!open && category.children?.length>0" class="size-5 h-5" aria-hidden="true"/>
                          <MinusIcon v-else-if="open   && category.children?.length>0" class="size-5 h-5"
                                     aria-hidden="true"/>
                        </span>
                            </DisclosureButton>
                          </h3>
                          <Transition
                              enter="transition duration-100 ease-out"
                              enterFrom="transform scale-95 opacity-0"
                              enterTo="transform scale-100 opacity-100"
                              leave="transition duration-75 ease-out"
                              leaveFrom="transform scale-100 opacity-100"
                              leaveTo="transform scale-95 opacity-0"
                          >
                            <DisclosurePanel class="pt-1 ps-4 bg-gray-50 rounded-b-lg">
                              <div class=" ">
                                <div v-for="(child, idx) in category.children??[]" :key="child.id" class="   ">
                                  <div @click="toggleCategory(child)"
                                       class="hover:bg-gray-100 cursor-pointer py-2 px-1">
                                    <input @click.stop.prevent tabindex="-1"
                                           :id="`filter-mobile-category-${child.id}`"
                                           :checked="params.category_ids?.includes(child.id)" type="checkbox"
                                           class="col-start-1 row-start-1 pointer-events-none appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                                    <label @click.prevent
                                           :for="`filter-mobile-category-${child.id}`"
                                           class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                                        child.name
                                      }}</label>
                                  </div>

                                </div>
                              </div>
                            </DisclosurePanel>
                          </Transition>
                        </Disclosure>
                      </template>
                      <template v-else>
                        <div @click="toggleCategory(category)"
                             :class="{'border-t border-gray-200':idx>0}"
                             class="hover:bg-gray-100   cursor-pointer py-3 px-1">
                          <input @click.stop.prevent tabindex="-1" :id="`filter-mobile-category-${category.id}`"
                                 :checked="params.category_ids?.includes(category.id)" type="checkbox"
                                 class="col-start-1 pointer-events-none row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"/>
                          <label @click.prevent :for="`filter-mobile-category-${category.id}`"
                                 class="min-w-0 mx-2 text-sm select-none cursor-pointer flex-1 text-gray-500">{{
                              category.name
                            }}</label>
                        </div>
                      </template>

                    </li>
                  </ul>

                </form>
              </aside>

              <!-- Product grid -->
              <div class="lg:col-span-3">
                <section v-if="products.length>0" class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">

                  <div class="lg:col-span-4">
                    <div
                        class="  mt-6   gap-y-3 gap-x-2 grid xs:grid-cols-1 sm:grid-cols-3   lg:grid-cols-3 xl:grid-cols-4 ">
                      <div class="bg-white   shadow-md rounded-lg    "
                           v-for="(p,idx) in products">
                        <div :id="p.id"
                             class="     flex flex-row sm:flex-col h-full    hover:scale-[101%] duration-300">
                          <div class="flex flex-col h-full justify-between p-3 w-full ">

                            <Link :href=" route( 'variation.view',{id:p.id,name:p.name})">
                              <div class="flex  sm:flex-col  ">
                                <div class="md:mx-auto sm:h-48 sm:w-full  h-24     w-32    ">
                                  <!--                <Image :data-lity="route('storage.variations')+`/${p.id}/thumb.jpg`"-->
                                  <!--                       classes="object-cover  h-full w-full  rounded-t-lg rounded-b   "-->
                                  <!--                       :src="route('storage.variations')+`/${p.id}/thumb.jpg`"></Image> -->
                                  <Image classes="object-cover  h-full w-full  rounded-lg    "
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

                                <div v-if="false" class="flex   w-full   justify-end items-center font-bold">
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

                            <div>
                              <div class="flex items-stretch justify-between my-2 text-xs ">
                                <Menu

                                    v-if="p.prices?.length" as="div"
                                    class="relative inline-block text-start">
                                  <div>
                                    <MenuButton ref="menuRefs" @mouseenter="openMenu(idx)"
                                                class="group bg-success-50 flex text-success  hover:bg-success-100 cursor-pointer font-bold shadow-md rounded-lg   p-2 ">
                                      <div> {{
                                          (p.prices?.length)
                                              ? `${p.prices.length === 1
                                                  ? p.prices[0].discount
                                                  : `%${p.prices[0].discount ?? '0'} ${__('until')} %${p.prices[p.prices.length - 1].discount ?? '0'}`
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
                                            v-for="(price,idx) in p.prices??[]" :key="`price-item-${p.id}-${idx}`"
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
                                <div v-if="p.showDiscount"
                                     class="font-bold bg-success-50 text-success rounded-lg shadow-md p-2 bg-white"> {{
                                    p.showDiscount
                                  }}
                                </div>
                              </div>
                              <div
                                  class="flex items-center justify-end my-2 ">
                                <div class="flex items-center "
                                     :class="{'line-through text-neutral-500':($page.props.is_auction && p.in_auction) || p.showPrice!=p.price}">
                                  {{ asPrice(Math.round(p.price)) }}

                                  <svg v-if="($page.props.is_auction && p.in_auction) || p.showPrice!=p.price"
                                       xmlns="http://www.w3.org/2000/svg"
                                       viewBox="0 0 14 14"
                                       class="fill-gray-500 h-5 w-5">
                                    <path fill-rule="evenodd"
                                          d="M3.057 1.742L3.821 1l.78.75-.776.741-.768-.749zm3.23 2.48c0 .622-.16 1.111-.478 1.467-.201.221-.462.39-.783.505a3.251 3.251 0 01-1.083.163h-.555c-.421 0-.801-.074-1.139-.223a2.045 2.045 0 01-.9-.738A2.238 2.238 0 011 4.148c0-.059.001-.117.004-.176.03-.55.204-1.158.525-1.827l1.095.484c-.257.532-.397 1-.419 1.403-.002.04-.004.08-.004.12 0 .252.055.458.166.618a.887.887 0 00.5.354c.085.028.178.048.278.06.079.01.16.014.243.014h.555c.458 0 .769-.081.933-.244.14-.139.21-.383.21-.731V2.02h1.2v2.202zm5.433 3.184l-.72-.7.709-.706.735.707-.724.7zm-2.856.308c.542 0 .973.19 1.293.569.297.346.445.777.445 1.293v.364h.18v-.004h.41c.221 0 .377-.028.467-.084.093-.055.14-.14.14-.258v-.069c.004-.243.017-1.044 0-1.115L13 8.05v1.574a1.4 1.4 0 01-.287.863c-.306.405-.804.607-1.495.607h-.627c-.061.733-.434 1.257-1.117 1.573-.267.122-.58.21-.937.265a5.845 5.845 0 01-.914.067v-1.159c.612 0 1.072-.082 1.38-.247.25-.132.376-.298.376-.499h-.515c-.436 0-.807-.113-1.113-.339-.367-.273-.55-.667-.55-1.18 0-.488.122-.901.367-1.24.296-.415.728-.622 1.296-.622zm.533 2.226v-.364c0-.217-.048-.389-.143-.516a.464.464 0 00-.39-.187.478.478 0 00-.396.187.705.705 0 00-.136.449.65.65 0 00.003.067c.008.125.066.22.177.283.093.054.21.08.352.08h.533zM9.5 6.707l.72.7.724-.7L10.209 6l-.709.707zm-6.694 4.888h.03c.433-.01.745-.106.937-.29.024.012.065.035.12.068l.074.039.081.042c.135.073.261.133.379.18.345.146.67.22.977.22a1.216 1.216 0 00.87-.34c.3-.285.449-.714.449-1.286a2.19 2.19 0 00-.335-1.145c-.299-.457-.732-.685-1.3-.685-.502 0-.916.192-1.242.575-.113.132-.21.284-.294.456-.032.062-.06.125-.084.191a.504.504 0 00-.03.078 1.67 1.67 0 00-.022.06c-.103.309-.171.485-.205.53-.072.09-.214.14-.427.147-.123-.005-.209-.03-.256-.076-.057-.054-.085-.153-.085-.297V7l-1.201-.5v3.562c0 .261.048.496.143.703.071.158.168.296.29.413.123.118.266.211.43.28.198.084.42.13.665.136v.001h.036zm2.752-1.014a.778.778 0 00.044-.353.868.868 0 00-.165-.47c-.1-.134-.217-.201-.35-.201-.18 0-.33.103-.447.31-.042.071-.08.158-.114.262a2.434 2.434 0 00-.04.12l-.015.053-.015.046c.142.118.323.216.544.293.18.062.325.092.433.092.044 0 .086-.05.125-.152z"
                                          clip-rule="evenodd"></path>
                                  </svg>


                                </div>
                                <div
                                    v-if="($page.props.is_auction &&  p.in_auction==true) || p.showPrice!=p.price"
                                    class="flex items-center ">

                                  <ArrowTrendingUpIcon class=" h-4 rotate-180 text-neutral-500 mx-2"/>
                                  <span v-if="false">  {{ asPrice(Math.round(p.auction_price)) }}</span>
                                  <span>  {{ asPrice(Math.round(p.showPrice)) }}</span>


                                </div>
                                <TomanIcon class="w-4 h-4 mx-2"/>

                              </div>
                              <div @click.self class="flex    min-w-[100%] items-center gap-x-1  ">

                                <CartItemButton
                                    @qtyChanged="(price,discount)=>{p.showPrice=price;p.showDiscount=discount} "
                                    :key="p.id" class="w-full "
                                    :price="p.price"
                                    :prices="Array.isArray(p.prices)?p.prices :[]"
                                    :product-id="p.id"/>

                              </div>
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
                <section v-show="loading" ref="loader">
                  <LoadingIcon v-show="loading" type="linear"/>
                </section>
              </div>
            </div>
          </section>

        </main>

      </div>
    </div>


  </Scaffold>

</template>

<script setup>
import {onMounted, reactive, ref} from 'vue'
import LoadingIcon from "@/Components/LoadingIcon.vue";
import TomanIcon from "@/Components/TomanIcon.vue";
import Image from "@/Components/Image.vue";
import Scaffold from "@/Layouts/Scaffold.vue";
import {Head, Link} from '@inertiajs/vue3';
import {
  PencilIcon,
  ArrowTrendingUpIcon,
  ChevronDownIcon,
  FunnelIcon,
  MinusIcon,
  PlusIcon,
  Squares2X2Icon
} from "@heroicons/vue/24/solid";
import SearchInput from "@/Components/SearchInput.vue";
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Navigation, Pagination, Scrollbar, A11y} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import CartItemButton from "@/Components/CartItemButton.vue";
import Selector from "@/Components/Selector.vue";
import DropdownMenu from "@/Components/DropdownMenu.vue";
import {usePage,} from "@inertiajs/vue3";
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {XMarkIcon} from '@heroicons/vue/24/outline'
import {dir, getQueryParams, __, log, getErrors, showToast} from "@/Composables/utils.js";

const sortOptions = [
  {name: __('most_popular'), params: {dir: 'DESC', order_by: 'sell_count'}, current: false},
  {name: __('cheapest'), params: {dir: 'ASC', order_by: 'price'}, current: false},
  {name: __('most_expensive'), params: {dir: 'DESC', order_by: 'price'}, current: false},
  {name: __('newest'), params: {dir: 'DESC', order_by: 'updated_at'}, current: false},
  {name: __('oldest'), params: {dir: 'ASC', order_by: 'updated_at'}, current: false},
]
const subCategories = [
  {name: 'Totes', href: '#'},
  {name: 'Backpacks', href: '#'},
  {name: 'Travel Bags', href: '#'},
  {name: 'Hip Bags', href: '#'},
  {name: 'Laptop Sleeves', href: '#'},
]
const filters = [
  {
    id: 'color',
    name: 'Color',
    options: [
      {value: 'white', label: 'White', checked: false},
      {value: 'beige', label: 'Beige', checked: false},
      {value: 'blue', label: 'Blue', checked: true},
      {value: 'brown', label: 'Brown', checked: false},
      {value: 'green', label: 'Green', checked: false},
      {value: 'purple', label: 'Purple', checked: false},
    ],
  },
  {
    id: 'category',
    name: 'Category',
    options: [
      {value: 'new-arrivals', label: 'New Arrivals', checked: false},
      {value: 'sale', label: 'Sale', checked: false},
      {value: 'travel', label: 'Travel', checked: true},
      {value: 'organization', label: 'Organization', checked: false},
      {value: 'accessories', label: 'Accessories', checked: false},
    ],
  },
  {
    id: 'size',
    name: 'Size',
    options: [
      {value: '2l', label: '2L', checked: false},
      {value: '6l', label: '6L', checked: false},
      {value: '12l', label: '12L', checked: false},
      {value: '18l', label: '18L', checked: false},
      {value: '20l', label: '20L', checked: false},
      {value: '40l', label: '40L', checked: true},
    ],
  },
]

const mobileFiltersOpen = ref(false)


// Props
defineProps({
  heroText: String
})

// Refs & reactive state
const products = ref([])
const categories = ref([])

const loading = ref(false)
const total = ref(0)
const loader = ref(null)
const menuRefs = ref([])
const params = reactive({
  page: 0,
  search: null,
  grade: null,
  products: [],
  order_by: null,
  dir: null,
  category_ids: [],
  brand_ids: [],
  province_id: null,
  city_id: null,
})
const modules = [Navigation, Pagination, Scrollbar, A11y]


function openMenu(index) {
  return
  const el = menuRefs.value[index].$el

  if (el?.getAttribute('data-headlessui-state') === 'open') {
    // el.setAttribute('data-headlessui-state', '')
  } else {
    // el.setAttribute('data-headlessui-state', 'open')
    el.click()
  }
}

function toggleBrand(item) {

  const id = item.id
  const set = new Set(params.brand_ids); // convert to Set
  set.has(id) ? set.delete(id) : set.add(id); // toggle
  params.brand_ids = Array.from(set);
  getData(0)
}

function toggleCategory(item) {
  const allIds = collectTreeIds(item)

  // Convert to a Set for efficient lookups
  const idSet = new Set(params.category_ids)

  const isSelected = idSet.has(item.id)

  if (isSelected) {
    // Remove item.id and all children recursively
    allIds.forEach(id => idSet.delete(id))
  } else {
    // Add item.id and all children recursively
    allIds.forEach(id => idSet.add(id))
  }

  // Convert back to array
  params.category_ids = Array.from(idSet)

  getData(0)
}

function collectTreeIds(item) {
  const ids = [item.id]

  if (Array.isArray(item.children)) {
    item.children.forEach(child => {
      ids.push(...collectTreeIds(child))
    })
  }

  return ids
}

// Scroll setup for infinite scroll
function setScroll(el) {
  window.onscroll = () => {
    const topOfElement = el.offsetTop
    const bottomOfElement = el.offsetTop + el.offsetHeight
    const bottomOfScreen = window.pageYOffset + window.innerHeight
    const topOfScreen = window.pageYOffset

    if (
        bottomOfScreen + 300 > topOfElement &&
        topOfScreen < bottomOfElement + 200 &&
        !loading.value
    ) {
      getData()
    }
  }
}

// Fetch data
function getData(page) {
  if (page === 0) {
    params.page = 1
    products.value = []
  }
  if (total.value > 0 && total.value <= products.value.length) return

  loading.value = true

  window.axios
      .get(route('variation.search'), {params})
      .then((response) => {
        total.value = response.data.total
        params.page = response.data.current_page + 1

        products.value = products.value.concat(response.data.data?.map(i => {
          i.prices = JSON.parse(i.prices);
          i.showPrice = i.price;
          return i
        }))
      })
      .catch((error) => {
        const err = getErrors(error)
        showToast('danger', err)
      })
      .finally(() => {
        loading.value = false
      })
}

// Lifecycle
onMounted(() => {
  setScroll(loader.value)
  Object.assign(params, getQueryParams(window.location) ?? {})
  getData()
})
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