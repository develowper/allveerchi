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
          <h1 class="text-2xl font-semibold">{{ __('admins_list') }}</h1>
        </div>
        <div v-if="hasAccess('admin:create')">
          <Link :href="route('admin.panel.admin.create')"
                class="inline-flex items-center  justify-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold  transition-all duration-500 text-white     hover:bg-green-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            {{ __('new_admin') }}
          </Link>
        </div>
      </div>
      <!-- Content -->
      <div class="px-2 flex flex-col   md:px-4">

        <div class="flex-col   bg-white  overflow-x-auto shadow-lg  rounded-lg">
          <div class="flex   items-center justify-start py-4 p-4">
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
                <div ref="adminMenu" data-te-dropdown-menu-ref
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

            <div class="relative mx-1">
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
              <input type="text" id="table-search-admins" v-model="params.search" @keydown.enter="getData()"
                     class="block p-1 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                     :placeholder="__('search')">
            </div>

            <!--            roles selector-->
            <div class="inline-flex" role="group">

              <div v-for="(s,idx) in $page.props.admin_roles"
                   type="button"
                   @click="(params.access_id==s.id?params.access_id=null: params.access_id=s.id);params.page=1;getData()"
                   class="inline-block border   border-1 w-16 p-2  text-center text-xs font-medium uppercase leading-normal  transition duration-150 ease-in-out hover:border-primary-accent-200   focus:border-primary-accent-200 focus:bg-secondary-50/50 focus:outline-none focus:ring-0 active:border-primary-accent-200 motion-reduce:transition-none dark:border-primary-400 dark:text-primary-300 dark:hover:bg-blue-950 dark:focus:bg-blue-950"
                   :class="`bg-gray-200 cursor-pointer ${idx==0?'rounded-s-lg':idx==$page.props.admin_roles.length-1 ?'rounded-e-lg':''} border-dark-500 ${s.id==params.access_id?  `text-white bg-green-500` :`text-gray-400 bg-white`}`"
                   data-twe-ripple-init
                   data-twe-ripple-color="light">
                {{ __(s.name) }}
              </div>


            </div>

          </div>
          <!--           table-->
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                  @click="params.order_by='fullname';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">  {{ __('fullname') }}</span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>

              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='agency_id';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('agency') }} </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='phone';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('phone') }} </span>
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
              <th scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='role';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('role') }}  </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>
              <th v-if="false" scope="col"
                  class="px-2 py-3   cursor-pointer duration-300 hover:text-gray-500 hover:scale-[105%]"
                  @click="params.order_by='access';params.dir=params.dir=='ASC'? 'DESC':'ASC'; params.page=1;getData()">
                <div class="flex items-center justify-center">
                  <span class="px-2">    {{ __('access') }}  </span>
                  <ArrowsUpDownIcon class="w-4 h-4 "/>
                </div>
              </th>


              <th scope="col" class="px-2 py-3">
                {{ __('actions') }}
              </th>
            </tr>
            </thead>
            <tbody class=" ">
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
                class="bg-white text-center border-b hover:bg-gray-50">
              <td class="w-4 p-4" @click="d.selected=!d.selected">
                <div class="flex items-center">
                  <input id="checkbox-table-search-1" type="checkbox" v-model="d.selected"
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">

                </div>
              </td>
              <td
                  class="flex  items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                <Image class="w-10 h-10 rounded-full" :src="`${route('storage.admins')}/${d.id}.jpg`"
                       :alt="cropText(d.fullname,5)"/>
                <Link v-if="hasAccess('admin:edit:*')" class="px-3 text-xs hover:text-gray-500"
                      :href="route('admin.panel.admin.edit',d.id)">
                  <div class="  font-semibold">{{ cropText(d.fullname, 30) }}</div>
                </Link>
                <div v-else class="  font-semibold">{{ cropText(d.fullname, 30) }}</div>
              </td>

              <td class="px-2 py-4    ">
                <div v-if="d.agency">
                  <div> {{ `(${d.agency.id})` }}</div>
                  <div> {{ `${d.agency.name || ''}` }}</div>

                </div>
              </td>

              <td class="px-8 py-4">
                {{ d.phone }}
              </td>

              <td class="px-2 py-4    " data-te-dropdown-ref>
                <button
                    :id="`dropdownStatusSetting${d.id}`"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="  min-w-[5rem]  px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]"
                    :class="`bg-${getStatus('user_statuses', d.status).color}-100 hover:bg-${getStatus('user_statuses', d.status).color}-200 text-${getStatus('user_statuses', d.status).color}-500`">
                  {{ getStatus('user_statuses', d.status).name }}
                </button>
                <ul v-show="hasAccess('admin:edit:status')" :ref="`statusMenu${d.id}`" data-te-dropdown-menu-ref
                    class="  absolute z-[1000]   m-0 hidden   list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block"
                    tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"
                    :aria-labelledby="`dropdownStatusSetting${d.id}`">

                  <li v-for="(s,ix) in  $page.props.user_statuses " role="menuitem"
                      @click=" edit ({'idx':idx,'id':d.id,'cmnd':'status','status':s.name}) "
                      class="   cursor-pointer   text-sm   transition-colors hover:bg-gray-100">
                    <div class="flex items-center justify-center    px-6 py-2   "
                         :class="` hover:bg-gray-200 text-${s.color}-500`">
                      {{ __(s.name) }}
                    </div>
                    <hr class="border-gray-200 ">
                  </li>

                </ul>
              </td>

              <td class="px-2 py-4    " data-te-dropdown-ref>
                <button
                    :id="`dropdownRole${d.id}`"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="  min-w-[5rem]  px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]"
                    :class="`bg-gray-100 hover:bg-gray-200 text-gray-500`">
                  {{ getAdminRole(d.access_id) || '-' }}
                </button>
                <ul v-show="hasAccess('admin:edit:role_id')" :ref="`roleMenu${d.id}`" data-te-dropdown-menu-ref
                    class="  absolute z-[1000]   m-0 hidden   list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block"
                    tabindex="-1" role="menu" aria-orientation="vertical" aria-label="Role menu"
                    :aria-labelledby="`dropdownRole${d.id}`">

                  <li v-for="(s,ix) in  $page.props.admin_roles  " role="menuitem"
                      @click=" edit ({'idx':idx,'id':d.id,'cmnd':'role','access_id':s.id }) "
                      class="   cursor-pointer   text-sm   transition-colors hover:bg-gray-100">
                    <div class="flex items-center justify-center    px-6 py-2   "
                         :class="` hover:bg-gray-200 text-gray-500`">
                      {{ __(s.name) }}
                    </div>
                    <hr class="border-gray-200 ">
                  </li>

                </ul>
              </td>


              <td v-if="false"
                  class="px-2 py-4    " data-te-dropdown-ref>
                <button
                    id="dropdownViewFee"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="  min-w-[5rem] bg-gray-100 hover:bg-gray-200 px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]"
                    :class="`bg-gray-100 hover:bg-gray-200 text-gray-500`"
                >
                  {{ (d.access || []).length }}
                </button>
                <ul ref="dropdownViewFeeMenu" data-te-dropdown-menu-ref
                    class="p-2  absolute z-[1000]    hidden   list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block"
                    tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"
                    aria-labelledby="dropdownViewFee">
                  <li
                      class="   text-sm  ">
                    <div class=" ">
                      <div v-for="(access,ix) in (d.access || [])" class=" px-6">
                        {{ access }}
                      </div>
                    </div>

                  </li>


                </ul>
              </td>

              <td class="px-2 py-4">
                <!-- Actions Group -->
                <div
                    class=" inline-flex rounded-md shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)]"
                    role="group">
                  <Link v-if="hasAccess('admin:edit:*')"
                        type="button" :href="route('admin.panel.admin.edit',d.id)"
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
        role: null,
        access_id: null,
      },
      data: [],
      pagination: {},
      toggleSelect: false,
      loading: false,
      error: null,
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
  },
  mounted() {
    this.tableWrapper = document.querySelector('table').parentElement;

    this.getData();

    // this.showDialog('danger', 'message',()=>{});
    // this.isLoading(false);
  },
  methods: {
    getData() {

      this.loading = true;
      this.data = [];
      window.axios.get(route('admin.panel.admin.search'), {
        params: this.params
      }, {})
          .then((response) => {
            this.data = response.data.data;
            this.data.forEach(el => {
              el.selected = false;
              el.accesses = el.accesses ? el.accesses.split(',') : [];
            });
            delete response.data.data;
            this.pagination = response.data;

            this.$nextTick(() => {
              this.initTableDropdowns();
              this.setTableHeight();
            });
          })

          .catch((error) => {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
              this.error = error.response.data;

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
            this.showToast('danger', error)
          })
          .finally(() => {
            // always executed
            this.loading = false;
          });
    },
    setTableHeight() {
      let a = window.innerHeight - this.tableWrapper.offsetTop;
      // this.tableWrapper.classList.add(`h-[60vh]`);
      this.tableWrapper.style.height = `${a}px`;
      // this.tableWrapper.firstChild.classList.add(`overflow-y-scroll`);
    },
    toggleAll() {

      this.toggleSelect = !this.toggleSelect;
      this.data.forEach(e => {
        e.selected = this.toggleSelect;
      });
    },
    edit(params) {
      this.isLoading(true);
      window.axios.patch(route('admin.panel.admin.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data.wallet) {
              this.data[params.idx].wallet = response.data.wallet;
              this.user.wallet = response.data.wallet;
            }

            if (response.data.status) {
              this.data[params.idx].status = response.data.status;
            }
            if (response.data.access_id) {
              this.data[params.idx].access_id = response.data.access_id;
            }
            if (response.data.access) {
              this.data[params.idx].access = response.data.access;
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

    }
  },

}
</script>
