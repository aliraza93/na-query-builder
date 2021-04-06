<template>
    <div>
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Generate CA</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="country_name">Country Name</label>
                                        <input type="text" id="country_name" class="form-control" placeholder="2 Letter Code">
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" class="form-control" placeholder="USA">
                                    </div>
                                    <div class="form-group">
                                        <label for="organization_unit_name">Organizaztion Unit Name</label>
                                        <input type="text" id="organization_unit_name" class="form-control" placeholder="Organization Unit Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="algorithm">Algorithm</label>
                                        <select id="algorithm" class="form-control">
                                            <option value="Algorithm">Algorithm</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="key_size">Key Size</label>
                                        <select id="key_size" class="form-control">
                                            <option value="Key Size">Key Size</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="common_nmae">Common Name</label>
                                        <input type="text" id="common_nmae" class="form-control" placeholder="CN">
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State or Province</label>
                                        <input type="text" id="state" class="form-control" placeholder="State or Province">
                                    </div>
                                    <div class="form-group">
                                        <label for="organization_name">Organization Name</label>
                                        <input type="text" id="organization_name" class="form-control" placeholder="Organization Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="validity_days">Validity Days</label>
                                        <input type="text" id="validity_days" class="form-control" placeholder="Validity Days">
                                    </div>
                                    <div class="form-group">
                                        <label for="hash_algorithm">Hash Algorithm</label>
                                        <select id="hash_algorithm" class="form-control">
                                            <option value="Hash Algorithm">Hash Algorithm</option>
                                        </select>
                                    </div>
                                    <button style="margin-top: 30px; float: right;" type="submit" class="btn btn-primary">Generate CA</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {

        };
    },
    created() {
        var _this = this;
        
    },

    mounted() {
        
    },
    
    methods: {
            
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