<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('edit_product')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
        <FolderPlusIcon class="h-7 w-7 mx-3"/>

        <h1 class="text-2xl font-semibold">{{ __('edit_product') }}</h1>

      </div>


      <div class="px-2  md:px-4">

        <div
            class="    mx-auto md:max-w-2xl   mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">


          <div
              class="flex flex-col mx-2 text-gray-500   col-span-2 w-full     px-2"
          >
            <div v-show="hasAccess('variation:edit:image')" class="flex-col   m-2  rounded-lg    w-full mx-auto    ">
              <div class="font-semibold">{{ __('main_product_image') }}</div>
              <div class="my-2 flex max-w-[150px]" v-if="$page.props.data">
                <ImageUploader mode="edit"
                               :link="route('admin.panel.variation.update')"
                               :preload="$page.props.data.thumb_img" ref="imageCropperThumb"
                               :label="__('product_image_jpg')" :for-id="$page.props.data.id"
                               :cropRatio="null" :id="'img-'+'thumb'"
                               class="   "/>
                <InputError class="mt-1 text-xs" :message="form.errors.image_thumb ? form.errors.image_thumb :null "/>

              </div>
              <div>{{ __('gallery') }}</div>

              <div class="my-2 flex flex-wrap items-stretch" v-if="$page.props.data">
                <div v-for="(data,idx) in $page.props.data.images"
                     class="m-1  max-w-[150px]   ">
                  <ImageUploader mode="edit"
                                 :link="route('admin.panel.variation.update')"
                                 :preload="$page.props.data.images[idx]" ref="imageCropper"
                                 :label="__('product_image_jpg')" :for-id="$page.props.data.id"
                                 :cropRatio="1" :id="'img-'+idx"
                                 class="   "/>
                  <InputError class="mt-1 text-xs" :message="form.errors.images ? form.errors.images.idx:null "/>
                </div>

              </div>

            </div>
            <form @submit.prevent="submit">

              <div class="my-2" v-show="hasAccess('variation:edit:name')">
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
              <div class="my-2" v-show="hasAccess('variation:edit:name_en')">
                <TextInput
                    id="name_en"
                    type="text"
                    :placeholder="__('name_en')"
                    classes="  "
                    v-model="form.name_en"
                    autocomplete="name_en"
                    :error="form.errors.name_en"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <Bars2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>
              </div>
              <div v-if="false" class="my-2">
                <Selector ref="gradeSelector" v-model="form.grade"
                          :data="$page.props.grades.map(e=>{return{id:e,name:e}})"
                          :error="form.errors.grade"
                          :label="__('grade')" classes=""
                          :id="`grade`">

                </Selector>
              </div>
              <div class="my-2" v-show="hasAccess('variation:edit:brand_id')">
                <Selector ref="brandSelector" v-model="form.brand_id"
                          :data="$page.props.brands "
                          :error="form.errors.brand_id"
                          :preload="$page.props.data.brand_id"
                          :label="__('brand')"
                          id="brand_id">
                  <template v-slot:append>
                    <div class="  p-3">
                      <Squares2X2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </Selector>
              </div>
              <div class="my-2" v-show="hasAccess('variation:edit:pack_id')">
                <Selector ref="packSelector" v-model="form.pack_id"
                          :preload="$page.props.data.pack_id"
                          :data="$page.props.packs"
                          @change="($e)=> {if(form.pack_id==-1)form.weight=1}"
                          :error="form.errors.pack_id"
                          :label="__('pack')" classes=""
                          :id="`pack`">

                </Selector>
              </div>
              <div class="my-2" v-show="hasAccess('variation:edit:weight')">
                <TextInput
                    :id="`weight`"
                    type="number"
                    :placeholder="`${__('unit_weight')} (${__('kg')})`"
                    :disabled="form.pack_id==-1? true:false"
                    classes=" p-2   min-w-[5rem]"
                    v-model="form.weight"
                    autocomplete="weight"
                    :error="form.errors.weight">

                </TextInput>
              </div>
              <div v-if="false" class="my-4">
                <UserSelector :multi="true" :colsData="['id','name','level', ]"
                              :labelsData="['id','name','level', ]"
                              :error="null"
                              :link="route('admin.panel.category.search') "
                              :label="__('categories')"
                              :id="'categories'"
                              v-model:selected="form.categories"
                              @change="($e)=>form.categories=$e"
                              :preload="(  this.data || {}).categories">
                  <template v-slot:selector="props">
                    <div v-if="(props.selectedText || []).length==0" :class=" 'py-2'"
                         class=" px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center ">
                      <div class="grow">
                        {{ __('select') }}
                      </div>
                    </div>

                    <template v-for="(text,idx) in props.selectedText">
                      <div :class=" 'py-2 m-1'"
                           class=" px-4 border border-gray-300 rounded hover:bg-gray-100 cursor-pointer flex items-center ">
                        <div class="grow">
                          {{ text }}
                        </div>
                        <div
                            class="bg-danger rounded p-2   cursor-pointer text-white hover:bg-danger-400"
                            @click.stop="props.clear(props.selectedItem[idx]  )">
                          <XMarkIcon class="w-5 h-5"/>

                        </div>
                      </div>
                      <InputError
                          :message="form.errors && form.errors[`repo_ids.${idx}`]?form.errors[`repo_ids.${idx}`]:null"/>
                    </template>
                  </template>
                </UserSelector>
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


export default {

  data() {
    return {
      data: this.$page.props.data || {},
      form: useForm({
        id: null,
        name: null,
        name_en: null,
        uploading: false,
        cmnd: null,
        categories: null,
        pack_id: null,
        weight: null,
        brand_id: null,

      }),
      img: null,
    }
  },
  components: {
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
    this.form.name_en = this.data.name_en;
    // this.form.categories = this.data.categories;
    this.form.pack_id = this.data.pack_id;
    this.$refs.packSelector.set(this.form.pack_id);
    this.form.weight = parseFloat(this.data.weight);
    this.form.cmnd = 'change-primary';
    this.form.brand_id = this.data.brand_id;
  },
  methods: {


    submit() {


      // this.form.category_id = this.$refs.categorySelector.selected;
      this.form.clearErrors();

      // this.isLoading(true, this.form.progress ? this.form.progress.percentage : null);
      // this.images = [];
      // for (let i = 0; i < this.$page.props.max_images_limit; i++) {
      //   let tmp = this.$refs.imageCropper[i].getCroppedData();
      //   if (tmp) this.images.push(tmp);
      // }
      this.form.patch(route('admin.panel.variation.update'), {
        preserveScroll: false,

        onSuccess: (data) => {
          if (this.$page.props.flash.status)
            this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);

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
