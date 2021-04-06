<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upload CA</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="common_nmae">Common Name</label>
                                <input type="text" id="common_nmae" class="form-control" placeholder="CN">
                            </div>
                            <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone>
                            <button style="margin-top: 30px; float: right;" type="submit" class="btn btn-primary">Generate CA</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
            <div class="col-md-2"></div>
        </div>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {

    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            dropzoneOptions: {
                // autoProcessQueue: false,
                url: base_url + 'proxy/ca/upload',
                thumbnailWidth: 150,
                maxFilesize: 5,
                acceptedFiles: ".txt, .pem",
                headers: {
                    "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    }
            }
        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {
            triggerSend() {
                this.$refs.myVueDropzone.processQueue();
            },
            uploadSuccess: function(file, response) {
                this.$refs.myVueDropzone.removeAllFiles()
            },
    },

    computed: {
        
    }
}
</script>

<style scoped>
    .form-group {
        margin-right: 10px;
    }
</style>