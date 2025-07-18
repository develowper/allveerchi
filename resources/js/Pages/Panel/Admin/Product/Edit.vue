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
              class="flex flex-col mx-2   col-span-2 w-full     px-2"
          >
            <div class="flex-col   m-2 items-center rounded-lg max-w-xs  w-full mx-auto    ">
              <div class="my-2" v-if=" hasAccess('product:edit:image' )">
                <ImageUploader :replace="true"
                               :preload="route('storage.products')+`/${$page.props.data.id}.jpg`"
                               mode="edit" :for-id="$page.props.data.id"
                               :link="route('admin.panel.product.update')"
                               ref="imageCropper" :label="__('product_image_jpg')" :cropRatio="1" id="img"
                               height="10" class="grow "/>
                <InputError class="mt-1 " :message="form.errors.img"/>
              </div>

            </div>
            <form @submit.prevent="submit">

              <div v-if=" hasAccess('product:edit:name' )" class="my-2">
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
              <div class="my-2" v-if=" hasAccess('product:edit:name_en' )">
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
              <div class="my-2" v-if=" hasAccess('product:edit:PN' )">
                <TextInput
                    id="PN"
                    type="text"
                    :placeholder="__('PN')"
                    classes="  "
                    v-model="form.PN"
                    autocomplete="PN"
                    :error="form.errors.PN"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <Bars2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>
              </div>

              <div class="my-4" v-if=" hasAccess('product:edit:categories' )">
                <TreeSelector :multi="true" :label="__('categories')" v-model="form.categories"
                              :data=" $page.props.categories"
                              :preload="$page.props.data.categories"
                              :error="form.errors.categories"/>
              </div>
              <div v-if="false" class="my-2">
                <TextInput
                    :id="`weight`"
                    type="number"
                    :placeholder="`${__('weight')} (${__('kg')})`"
                    :disabled="form.pack_id==1? true:false"
                    classes=" "
                    v-model="form.weight"
                    autocomplete="weight"
                    :error="form.errors.weight">
                  <template v-slot:prepend>
                    <div class="  p-3">
                      <ScaleIcon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>
              </div>
              <div class="my-4" v-if=" hasAccess('product:edit:tags' )">
                <TagInput ref="tags" :multi="true" :placeholder="__('tags')" v-model="form.tags"
                          :error="form.errors.tags"/>
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
  ScaleIcon,
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
import TreeSelector from "@/Components/TreeSelector.vue";


export default {

  data() {
    return {
      data: this.$page.props.data || {},
      form: useForm({

        id: null,
        name: null,
        name_en: null,
        uploading: false,
        category_id: false,
        tags: null,
        weight: null,
        PN: null,
        categories: null,


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
    ScaleIcon,
    TreeSelector,
  },
  created() {

  },
  mounted() {

    // console.log(this.$page.props.categories);


    this.form.id = this.data.id;
    this.form.name = this.data.name;
    this.form.name_en = this.data.name_en;
    this.form.PN = this.data.PN;
    this.form.weight = parseFloat(this.data.weight);
    this.form.category_id = this.data.category_id;
    this.form.categories = this.data.categories;
    this.form.tags = this.data.tags;


    this.$refs.tags.set(this.data.tags);

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
      this.form.patch(route('admin.panel.product.update'), {
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
