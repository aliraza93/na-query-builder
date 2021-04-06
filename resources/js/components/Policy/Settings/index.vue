<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Systsem Default Behaviour</h4>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox"> 
                                    <input class="custom-control-input" type="checkbox" value="" id="default_allow">
                                    <label class="custom-control-label" for="default_allow">Default Allow</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox"> 
                                    <input class="custom-control-input" checked type="checkbox" value="" id="default_block">
                                    <label class="custom-control-label" for="default_block">Default Block</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox"> 
                                    <input class="custom-control-input" type="checkbox" value="" id="default_policy">
                                    <label class="custom-control-label" for="default_policy">Default Policy</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary mt-1">Save</button>
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

export default {
}
</script>