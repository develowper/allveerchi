<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('panel')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-between px-4 py-2 text-primary-500 border-b md:py-4">
        <div class="flex">
          <Bars2Icon class="h-7 w-7 mx-3"/>
          <h5 class="  font-semibold">{{ __('catalog_list') }}</h5>
        </div>
        <div>
          <Link :href="route('admin.panel.catalog.create')"
                class="inline-flex items-center  justify-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold  transition-all duration-500 text-white     hover:bg-green-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            {{ __('new_catalog') }}
          </Link>
        </div>
      </div>
      <!-- Content -->
      <div class="px-2 flex flex-col   md:px-4">

        <div class="flex-col   bg-white  overflow-x-auto shadow-lg  rounded-lg">
          <div class="flex   items-center justify-between py-4 p-4">
            <!--              Dropdown Actions-->
            <div>
              <div class="relative mx-1  " data-te-dropdown-ref>
                <button
                    id="dropdownActionsSetting"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class=" inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5">

                  <span class="sr-only">table actions</span>
                  <span>{{ __('bulk_actions') }}</span>
                  <ChevronDownIcon class="h-4 w-4 mx-1"/>
                </button>

                <!--     menu -->
                <div ref="actionsMenu" data-te-dropdown-menu-ref
                     class="min-w-[12rem] absolute z-[1000] float-start m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg [&[data-te-dropdown-show]]:block"
                     tabindex="-1" role="menu" aria-orientation="vertical" aria-label="Actions menu"

                     aria-labelledby="dropdownActionsSetting">

                </div>
              </div>
            </div>
            <!--              Dropdown Paginate-->
            <div class="flex items-center">
              <div class="relative mx-1  " data-te-dropdown-ref>
                <button
                    id="dropdownPaginate"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class=" inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5">

                  <span class="sr-only">table actions</span>
                  <span>{{ params.paginate }}</span>
                  <ChevronDownIcon class="h-4 w-4 mx-1"/>
                </button>

                <!--     menu -->
                <div ref="userMenu" data-te-dropdown-menu-ref
                     class="min-w-[12rem] absolute z-[1000] start-0 text-gray-500  m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg [&[data-te-dropdown-show]]:block"
                     tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"

                     aria-labelledby="dropdownPaginate">
                  <div v-for=" num in $page.props.pageItems " class="">
                    <div @click="params.paginate=num;getData()" role="menuitem"
                         class=" cursor-pointer  select-none block  p-2 px-6 text-sm   transition-colors hover:bg-gray-100">
                      {{ num }}
                    </div>
                    <hr class="border-gray-200 ">
                  </div>
                </div>
              </div>

              <!--                Paginate-->
              <Pagination @paginationChanged="paginationChanged" :pagination="pagination"/>
            </div>

            <div class="relative ">
              <label for="table-search" class="sr-only">Search</label>
              <div
                  class="absolute inset-y-0 cursor-pointer text-gray-500 hover:text-gray-700  start-0 flex items-center px-3  ">
                <MagnifyingGlassIcon @click=" getData() " class="w-4 h-4 "/>
              </div>
              <div
                  class="absolute inset-y-0 end-0 text-gray-500 flex items-center px-3 cursor-pointer hover:text-gray-700  "
                  @click="params.search=null; getData() ">
                <XMarkIcon class="w-4 h-4 "/>
              </div>
              <input type="text" id="table-search-users" v-model="params.search" @keydown.enter="getData()"
                     class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                     :placeholder="__('search')">
            </div>
          </div>
          <!--           table-->
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50">
            <!--         table header-->
            <tr class="text-sm text-center">
              <th scope="col" class="p-4" @click="toggleAll">
                <div class="flex items-center">
                  <input id="checkbox-all-search" type="checkbox" v-model="toggleSelect"
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                  <label for="checkbox-all-search" class="sr-only">checkbox</label>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='name_fa';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">  {{ __('name_fa') }}</span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>

              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='name_en';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('name_en') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='pn';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('pn') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='price';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('price') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>

              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='image_indicator';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('image_indicator') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='image_url';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('image_url') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='in_shop';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('shop_count') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='in_repo';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('repository_count') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>

              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='status';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('status') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>


              <th scope="col" class="px-2 py-3">
                {{ __('actions') }}
              </th>
            </tr>
            </thead>
            <tbody class="text-xs ">
            <tr v-if="loading" v-for="i in 3"
                class="animate-pulse bg-white text-center border-b hover:bg-gray-50">
              <td class="w-4 p-4">
                <div class="flex items-center">
                  <input id="checkbox-table-search-1" type="checkbox"
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">

                </div>
              </td>
              <td
                  class="flex  items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <div class="w-10 h-10 rounded-full"
                />
                <div class="px-3">
                  <div class="text-base bg-gray-200 px-5 py-2 rounded-lg  "></div>
                  <div class="font-normal text-gray-500"></div>
                </div>
              </td>
              <td class="px-2 py-4 ">
                <div class="bg-gray-200 px-5 py-2 rounded-lg">

                </div>
              </td>
              <td class="px-2 py-4 ">
                <div class="bg-gray-200 px-5 py-2 rounded-lg">

                </div>
              </td>
              <td class="px-2 py-4 ">
                <div class="bg-gray-200 px-5 py-2 rounded-lg"></div>
              </td>
              <td class="px-2 py-4">
                <div
                    class="  justify-center bg-gray-200 px-5 py-3 rounded-lg  items-center text-center rounded-md "
                >

                </div>
              </td>
              <td class="px-2 py-4">
                <div class="bg-gray-200 px-5 py-2 rounded-lg"></div>
              </td>
              <td class="px-2 py-4">
                <!-- Actions Group -->
                <div
                    class="  bg-gray-200 px-5 py-4 rounded-lg rounded-md   "
                    role="group">

                </div>
              </td>
            </tr>
            <tr v-for="(d,idx) in data"
                class="text-center border-b hover:bg-gray-50" :class="{'bg-gray-50':idx%2==1}">
              <td class="w-4 p-4" @click="d.selected=!d.selected">
                <div class="flex items-center">
                  <input id="checkbox-table-search-1" type="checkbox" v-model="d.selected"
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">

                </div>
              </td>
              <td
                  class="flex  items-center px-2 py-4 text-gray-900 whitespace-nowrap">
                <!--                <Image class="w-10 h-10 cursor-pointer rounded-full" :src="`${route('storage.catalogs')}/${d.id}.jpg`"-->
                <!--                       :data-lity="`${route('storage.catalogs')}/${d.id}.jpg`"-->
                <!--                       :alt="cropText(d.title,5)"/>-->
                <Link class="px-2 hover:text-gray-500" :href="route('admin.panel.catalog.edit',d.id)">
                  <div class="text-sm font-semibold">{{ d.name_fa }}</div>
                  <div class="font-normal text-gray-500">{{ }}</div>
                </Link>
              </td>


              <td class="px-2 py-4">
                {{ d.name_en }}
              </td>

              <td class="px-2 py-4">
                {{ d.pn }}
              </td>

              <td class="px-2 py-4    ">
                <button
                    @click="d.idx=idx;d.cmnd='change-price';d.new_prices=d.prices ==null || d.prices.length ==0 ? [{}]:d.prices ;d.new_price=d.price;d.new_auction_price=d.auction_price; selected=d; "
                    id="PriceId"
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="  min-w-[5rem]    p-2 cursor-pointer items-center text-center rounded-md  "
                    :class="`bg-indigo-50 border border-indigo-300 hover:bg-indigo-200 text-indigo-500`"
                >
                  {{ (d.prices || [{price: '?'}]).map((i) => asPrice(i.price)).join('|') }}
                </button>

              </td>
              <td class="px-2 py-4">
                {{ d.image_indicator }}
              </td>
              <td class="px-2 py-4">
                <a class="hover:text-primary" v-if="d.image_url" :href="d.image_url">{{ d.image_url }}</a>
                <div v-else>-</div>
              </td>
              <td class="px-2 py-4">
                {{ d.in_shop }}
              </td>
              <td class="px-2 py-4">
                {{ d.in_repo }}
              </td>

              <td class="px-2 py-4    " data-te-dropdown-ref>
                <button
                    :id="`dropdownStatusSetting${d.id}`"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="  min-w-[5rem]  px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]"
                    :class="`bg-${getStatus('catalog', d.status).color}-100 hover:bg-${getStatus('catalog', d.status).color}-200 text-${getStatus('catalog', d.status).color}-500`">
                  {{ getStatus('catalog', d.status).name }}
                </button>
                <ul :ref="`statusMenu${d.id}`" data-te-dropdown-menu-ref
                    class="  absolute z-[1000]   m-0 hidden   list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block"
                    tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"
                    :aria-labelledby="`dropdownStatusSetting${d.id}`">

                  <li v-if="d.status=='active'  " role="menuitem"
                      @click="edit({'idx':idx,'id':d.id,'cmnd':'inactive'})"
                      class="   cursor-pointer   text-sm   transition-colors hover:bg-gray-100">
                    <div class="flex items-center text-danger  px-6 py-2 justify-between ">
                      <span class="bg-danger mx-1  animate-pulse px-1 py-1 rounded "></span>
                      {{ __('inactive') }}
                    </div>
                    <hr class="border-gray-200 ">
                  </li>
                  <li v-if="d.status=='review' || d.status=='block'  " role="menuitem"
                      class="   cursor-pointer   text-sm text-gray-500 transition-colors hover:bg-gray-100">
                    <div class="flex items-center  px-6 py-2 justify-between ">
                      <span v-if="d.status=='review'">{{ __('active_after_review') }}</span>
                      <span v-if="d.status=='block'">{{ __('not_available') }}</span>
                    </div>
                    <hr class="border-gray-200 ">
                  </li>
                  <li v-if="d.status=='inactive'  " role="menuitem"
                      @click="edit({'idx':idx,'id':d.id,'cmnd':'activate'})"
                      class="   cursor-pointer   text-sm text-primary-500 transition-colors hover:bg-gray-100">
                    <div class="flex items-center  px-6 py-2 justify-between ">
                      {{ __('activate') }}
                    </div>
                    <hr class="border-gray-200 ">
                  </li>
                </ul>
              </td>


              <td class="px-2 py-4">
                <!-- Actions Group -->
                <div
                    class=" inline-flex rounded-md shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                    role="group">
                  <Link
                      type="button" :href="route('admin.panel.catalog.edit',d.id)"
                      class="inline-block rounded  bg-orange-500 text-white px-6  py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-orange-400   focus:outline-none focus:ring-0  "
                      data-te-ripple-init
                      data-te-ripple-color="light">
                    {{ __('edit') }}
                  </Link>

                  <!--                  <button -->
                  <!--                      type="button"-->
                  <!--                      class="inline-block rounded-e bg-teal-500 px-6 py-2 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-teal-400   focus:outline-none focus:ring-0  "-->
                  <!--                      data-te-ripple-init-->
                  <!--                      data-te-ripple-color="light">-->
                  <!--                    {{ __('charge') }}-->
                  <!--                  </button>-->
                </div>
              </td>
            </tr>

            </tbody>
          </table>
          <!--Modals-->

          <div v-if="selected" class="relative z-[1050]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10  w-screen overflow-y-auto">
              <div @click.self="selected=null;errors={}"
                   class="flex min-h-full   justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-auto rounded-lg bg-white   shadow-xl transition-all sm:my-8  w-full   m-2 ">
                  <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class=" flex flex-col items-stretch">
                      <div class="flex items-center  gap-2">
                        <div
                            class="  flex text-warning  h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-warning-100 sm:mx-0 sm:h-10 sm:w-10">
                          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                               fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                          </svg>
                        </div>
                        <h3 class="text-base     text-gray-900" id="modal-title">
                          {{
                            [selected.name_fa, selected.name_en, selected.pn].join(' | ')
                          }}
                        </h3>
                      </div>
                      <div class="m-2  text-start">
                        <!--                         modal body-->
                        <div class="mt-2">

                          <div v-if="selected.cmnd=='change-price'"
                               class="   text-sm text-gray-500 ">
                            <span v-if="false" class="text-xs py-2 text-danger-500">{{ __('help_price') }}</span>
                            <table class="table-auto my-2  text-sm   text-gray-500 ">
                              <thead>
                              <tr>
                                <th>{{ __('from') }}</th>
                                <th>{{ __('until') }}</th>
                                <th v-if="false">{{ __('type') }}</th>
                                <th>{{ __('price') }}</th>
                                <th>{{ __('actions') }}</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="(p,idx) in selected.new_prices">
                                <td>
                                  <input :class="{'border-2 border-red-500':errors[`new_prices.${idx}.from`]}"
                                         class="w-24 px-1  text-sm border-gray-400 rounded" type="number"
                                         v-model="p.from">
                                </td>
                                <td>
                                  <input :class="{'border-2 border-red-500':errors[`new_prices.${idx}.to`]}"
                                         class="w-24  px-1 text-sm border-gray-400 rounded" type="number"
                                         v-model="p.to">
                                </td>
                                <td v-if="false">
                                  <select :class="{'border-2 border-red-500':errors[`new_prices.${idx}.type`]}"
                                          class="grow rounded  border-gray-400 cursor-pointer" name=""

                                          :id=" `priceTypeSelector` " v-model="p.type">
                                    <option class="text-start   rounded   m-1"
                                            v-for="d in $page.props.price_types  "
                                            :value="d.id">
                                      <div class="p-2"> {{ __(d.name) }}</div>
                                    </option>
                                  </select>

                                </td>
                                <td>
                                  <input :class="{'border-2 border-red-500':errors[`new_prices.${idx}.price`]}"
                                         class="w-24 px-1  text-sm border-gray-400 rounded" type="number"
                                         v-model="p.price">
                                </td>
                                <td class="">
                                  <div class="flex items-center gap-1 mx-2">
                                    <button
                                        @click="selected.new_prices.splice(idx+1,0,{})   "
                                        class="bg-green-500 rounded-md hover:bg-green-400 hover:cursor-pointer text-white ">
                                      <ChevronDownIcon class="w-6 h-6 m-2"/>
                                    </button>
                                    <button
                                        @click="selected.new_prices.splice(idx,1)   "
                                        class="bg-red-500 rounded-md hover:bg-red-400 hover:cursor-pointer text-white ">
                                      <TrashIcon class="w-6 h-6 m-2"/>
                                    </button>
                                    <button
                                        @click="selected.new_prices.splice(idx ,0,{})   "
                                        class="bg-green-500 rounded-md hover:bg-green-400 hover:cursor-pointer text-white ">
                                      <ChevronUpIcon class="w-6 h-6 m-2"/>
                                    </button>
                                  </div>
                                </td>
                              </tr>
                              </tbody>


                            </table>
                            <button
                                class="bg-success-200 text-success-700 p-2 rounded-lg  hover:bg-success-300 w-full"
                                @click="edit({'idx':selected.idx ,'id':selected.id,'cmnd':'change-price','new_prices':selected.new_prices,  })">
                              {{ __('accept') }}
                            </button>
                          </div>
                          <button class="bg-gray-200 my-2 text-gray-700 p-2 rounded-lg  hover:bg-gray-300 w-full"
                                  @click="selected=null;errors={}">
                            {{ __('cancel') }}
                          </button>

                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </template>


  </Panel>
</template>

<script>
import Scaffold from "@/Layouts/Scaffold.vue";
import Panel from "@/Layouts/Panel.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import {
  Bars2Icon,
  MagnifyingGlassIcon,
  ChevronDownIcon,
  HomeIcon,
  XMarkIcon,
  ArrowsUpDownIcon,
  TrashIcon,
  ChevronUpIcon,

} from "@heroicons/vue/24/outline";
import Image from "@/Components/Image.vue"
import Tooltip from "@/Components/Tooltip.vue"
import {Dropdown} from "tw-elements";

export default {
  data() {
    return {
      params: {
        page: 1,
        search: null,
        paginate: this.$page.props.pageItems[0],
        order_by: null,
        dir: 'DESC',
      },
      selected: null,
      data: [],
      pagination: {},
      toggleSelect: false,
      loading: false,
      error: null,
      errors: {},
    }
  },
  components: {
    Head,
    Link,
    HomeIcon,
    ChevronDownIcon,
    Panel,
    Bars2Icon,
    Image,
    MagnifyingGlassIcon,
    XMarkIcon,
    Pagination,
    ArrowsUpDownIcon,
    Tooltip,
    TrashIcon,
    ChevronUpIcon,
  },
  mounted() {

    this.getData();

    // this.showDialog('danger', 'message',()=>{});
    // this.isLoading(false);
  },
  methods: {
    getData() {

      this.loading = true;
      this.data = [];
      window.axios.get(route('admin.panel.catalog.search'), {
        params: this.params
      }, {})
          .then((response) => {
            this.data = response.data.data;
            this.data.forEach(el => {
              el.selected = false;
            });
            delete response.data.data;
            this.pagination = response.data;

            this.$nextTick(() => {

              this.initTableDropdowns();
            });

          })

          .catch((error) => {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
              this.error = error.response.data ? error.response.data.message ? error.response.data.message : error.response.data : this.__('response_error');

            } else if (error.request) {
              // The request was made but no response was received
              // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
              // http.ClientRequest in node.js
              console.log(error.request);
              this.error = error.request;
            } else {
              // Something happened in setting up the request that triggered an Error
              console.log('Error', error.message);
              this.error = error.message;
            }
            console.log(error.config);
            this.showToast('danger', this.error)
          })
          .finally(() => {
            // always executed
            this.loading = false;
          });
    },
    initTableDropdowns() {
      const dropdownElementList = [].slice.call(document.querySelectorAll('td [data-te-dropdown-toggle-ref]'));
      window.dropdownList = dropdownElementList.map((dropdownToggleEl) => {
        let d = new Dropdown(dropdownToggleEl);
        dropdownToggleEl.addEventListener('click', function (event) {
          d.toggle();
        })
        return d;
      });
    },
    toggleAll() {

      this.toggleSelect = !this.toggleSelect;
      this.data.forEach(e => {
        e.selected = this.toggleSelect;
      });
    },
    edit(params) {
      this.isLoading(true);
      window.axios.patch(route('admin.panel.catalog.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data.charge) {
              this.data[params.idx].charge = response.data.charge;
              this.user.wallet = response.data.wallet;
            }
            if (response.data.status) {
              this.data[params.idx].status = response.data.status;
            }
            if (response.data.view_fee) {
              this.data[params.idx].view_fee = response.data.view_fee;
            }
            if (response.data.meta) {
              this.data[params.idx].meta = response.data.meta;
              this.user.meta_wallet = response.data.meta_wallet;
            }
            if (response.data.prices) {
              this.data[params.idx].prices = response.data.prices;
            }

          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {
              if (error.response.data.charge) {
                this.data[params.idx].charge = error.response.data.charge;
              }
              if (error.response.data.view_fee) {
                this.data[params.idx].view_fee = error.response.data.view_fee;
              }
              if (error.response.data.meta) {
                this.data[params.idx].meta = error.response.data.meta;
              }
            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.isLoading(false);
          });
    },
    paginationChanged(data) {

      this.params.page = data.page;
      this.getData();
    },
    bulkAction(cmnd) {
      if (this.data.filter(e => e.selected).length == 0) {
        this.showToast('danger', this.__('nothing_selected'));
        return;
      }
      this.isLoading(true);
      const params = {
        cmnd: cmnd, data: this.data.reduce((result, el) => {
          if (el.selected) result.push(el.id);
          return result;
        }, [])
      };

      window.axios.patch(route('admin.panel.catalog.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data && response.data.results) {
              const res = response.data.results;
              for (let i in this.data)
                for (let j in res)
                  if (res[j].id == this.data[i].id) {
                    this.data[i].status = res[j].status;
                    break;
                  }
            }

          })

          .catch((error) => {
            this.error = this.getErrors(error);

            this.showToast('danger', this.error);
          })
          .finally(() => {
            this.isLoading(false);
          });
    }
  },

}
</script>
