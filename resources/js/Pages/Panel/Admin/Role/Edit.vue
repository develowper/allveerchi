<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('new_access')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
        <FolderPlusIcon class="h-7 w-7 mx-3"/>

        <h1 class="text-2xl font-semibold">{{ __('edit_access') }}</h1>

      </div>


      <div class="px-2  md:px-4">

        <div
            class="    mx-auto md:max-w-2xl   mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">


          <div
              class="flex flex-col mx-2   col-span-2 w-full     px-2"
          >
            <div class="flex-col   m-2 items-center rounded-lg max-w-xs  w-full mx-auto    ">
              <div v-if="false" class="my-2">
                <ImageUploader ref="imageCropper" :label="__('image_cover_jpg')" cropRatio="1.25" id="img"
                               height="10" class="grow "/>
                <InputError class="mt-1 " :message="form.errors.img"/>
              </div>

            </div>
            <form @submit.prevent="submit">

              <div class="my-2" v-show="hasAccess('role:edit:name')">
                <TextInput
                    id="name"
                    type="text"
                    :placeholder="__('name')"
                    classes="  "
                    v-model="form.name"
                    autocomplete="name"
                    :error="form.errors.name"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <Bars2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>
              </div>
              <div class="my-2" v-show="hasAccess('role:edit:agency_level')">
                <Selector
                    v-show="$page.props.agency_types.filter((e)=>$page.props.agency && e.level>=$page.props.agency.level).length>0"
                    ref="typeSelector"
                    :data="$page.props.agency_types.filter((e)=>$page.props.agency && e.level>=$page.props.agency.level)"
                    :label="__('agency_type')"
                    :error="form.errors.type_id"
                    id="type_id" v-model="form.type_id">
                  <template v-slot:append>
                    <div class="  p-3">
                      <Squares2X2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </Selector>
              </div>
              <div v-show="hasAccess('role:edit:accesses')">

                <BaseTree treeLine dir="ltr" class="mtl-tree text-gray-600 text-sm p-1" v-model="form.accesses"
                          ref="tree"
                          @check:node="()=>{}">
                  <template #default="{ node, stat }" class="  ">

                    <div
                        :class="[`${stat.children.length && stat.level==1 ? `my-1 border-b  text-gray-900 font-bold  `:''}`,`${  stat.level==2 ? `     text-gray-500 font-bold  `:''}`]"
                        class="      flex items-center  ">
                      <OpenIcon
                          v-if="stat.children.length && false"
                          :open="stat.open"
                          class="mtl-mr   border rounded-full h-3 w-3 mx-1  p-3   bg-gray-100 hover:bg-gray-100 cursor-pointer     hover:scale-[120%] duration-200"
                          @click="stat.open = !stat.open"
                      />

                      <input @click="toggleItem(node)"
                             class="mtl-checkbox mtl-mr rounded  p-2 cursor-pointer hover:text-primary-300 text-primary-500 focus:border-primary"
                             type="checkbox" @change="node.status=stat.checked?'active':'inactive'"
                             v-model="node.checked"
                      />
                      <span @click="toggleItem(node)"
                            class="mtl-ml p-1 select-none  cursor-pointer">{{ __(node.text) }} </span>


                    </div>
                  </template>
                </BaseTree>
              </div>

              <div v-if="form.progress" class="shadow w-full bg-grey-light m-2   bg-gray-200 rounded-full">
                <div
                    class=" bg-primary rounded  text-xs leading-none py-[.1rem] text-center text-white duration-300 "
                    :class="{' animate-pulse': form.progress.percentage <100}"
                    :style="`width: ${form.progress.percentage }%`">
                  <span class="animate-bounce">{{ form.progress.percentage }}</span>
                </div>
              </div>

              <div class="    mt-4">

                <PrimaryButton @click="submit" type="button" class="w-full flex items-center justify-center"
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                  <LoadingIcon class="w-4 h-4 mx-3 " v-if="  form.processing"/>
                  <span class=" text-lg  ">  {{ __('register_info') }} </span>
                </PrimaryButton>

              </div>

            </form>
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
import {
  ChevronDownIcon,
  HomeIcon,
  UserIcon,
  EyeIcon,
  FolderPlusIcon,
  Bars2Icon,
  LinkIcon,
  Squares2X2Icon,
  PencilSquareIcon,
  SignalIcon,
  ChatBubbleBottomCenterTextIcon,
  PencilIcon,
  XMarkIcon,
} from "@heroicons/vue/24/outline";
import {QuestionMarkCircleIcon,} from "@heroicons/vue/24/solid";
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import RadioGroup from '@/Components/RadioGroup.vue'
import LoadingIcon from "@/Components/LoadingIcon.vue";
import Popover from "@/Components/Popover.vue";
import Tooltip from "@/Components/Tooltip.vue";
import TagInput from "@/Components/TagInput.vue";
import ImageUploader from "@/Components/ImageUploader.vue";
import Selector from "@/Components/Selector.vue";
import ProvinceCounty from "@/Components/ProvinceCounty.vue";
import PhoneFields from "@/Components/PhoneFields.vue";
import SocialFields from "@/Components/SocialFields.vue";
import Article from "@/Components/Article.vue";
import TextEditor from "@/Components/TextEditor.vue";
import UserSelector from "@/Components/UserSelector.vue";
import AddressSelector from "@/Components/AddressSelector.vue";
import CitySelector from "@/Components/CitySelector.vue";
import {BaseTree, OpenIcon} from "@he-tree/vue";


export default {

  data() {
    return {
      data: this.$page.props.data || {},

      form: useForm({
        id: null,
        name: null,
        type_id: null,
        accesses: [],


      }),
    }
  },
  components: {
    OpenIcon,
    BaseTree,
    TextEditor,
    ImageUploader,
    LoadingIcon,
    Head,
    Link,
    HomeIcon,
    ChevronDownIcon,
    Panel,
    InputLabel,
    TextInput,
    InputError,
    PrimaryButton,
    RadioGroup,
    UserIcon,
    EyeIcon,
    Checkbox,
    Popover,
    Tooltip,
    FolderPlusIcon,
    Bars2Icon,
    LinkIcon,
    TagInput,
    QuestionMarkCircleIcon,
    Selector,
    Squares2X2Icon,
    ProvinceCounty,
    PhoneFields,
    SocialFields,
    PencilSquareIcon,
    Article,
    SignalIcon,
    ChatBubbleBottomCenterTextIcon,
    PencilIcon,
    UserSelector,
    XMarkIcon,
    AddressSelector,
    CitySelector,
  },
  created() {

  },
  mounted() {

    // console.log(this.data);


    this.form.id = this.data.id;
    this.form.name = this.data.name;
    this.form.accesses = this.data.accesses;
    this.form.type_id = this.$page.props.agency_types.find(i => i.level == this.data.agency_level)?.id;
    this.$refs.typeSelector.selecteds = this.form.type_id;


  },
  methods: {
    toggleItem(item, check = null) {
      item.checked = check !== null ? check : !item.checked;

      for (let idx in item.children) {
        this.toggleItem(item.children[idx], item.checked)
      }
      // find parent  and toggle
      // const li = item.key?.lastIndexOf(':');
      // const parentKey = li !== -1 ? item.key?.substring(0, li) : '';

    },
    submit() {


      // this.form.category_id = this.$refs.categorySelector.selected;
      this.form.clearErrors();

      // this.isLoading(true, this.form.progress ? this.form.progress.percentage : null);
      // this.images = [];
      // for (let i = 0; i < this.$page.props.max_images_limit; i++) {
      //   let tmp = this.$refs.imageCropper[i].getCroppedData();
      //   if (tmp) this.images.push(tmp);
      // }
      this.form.patch(route('admin.panel.role.update'), {
        preserveScroll: false,

        onSuccess: (data) => {
          if (this.$page.props.flash.status)
            this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);
          window.location.reload();
        },
        onError: () => {
          this.showToast('danger', Object.values(this.form.errors).join("<br/>"));
        },
        onFinish: (data) => {
          // this.isLoading(false,);
          if (this.$page.props.flash.status)
            this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);
        },
      });
    },

  },

}
</script>
