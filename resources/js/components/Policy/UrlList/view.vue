<template>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-bottom p-1">
                    <div class="head-label">
                        <h6 class="mb-0">Basic Information</h6>
                    </div>
                </div>
                <div class="card-body mt-1">
                    <form>
                        <div class="form-group">
                            <label>List Title</label>
                            <input type="text" class="form-control" disabled :value="url.list_title">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">URL List Details</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="url-list-tab" data-toggle="tab" href="#url-list" aria-controls="url-list" role="tab" aria-selected="true"><i data-feather="list"></i> List of URLs</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <url-list-tab :url="url"></url-list-tab>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UrlListTab from './tabs/UrlListTab.vue'
export default {
    components: { UrlListTab },
    props: ['url']
}
</script>