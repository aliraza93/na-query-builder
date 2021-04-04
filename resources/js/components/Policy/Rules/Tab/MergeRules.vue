<template>
    <div class="tab-pane" id="merge-rules" aria-labelledby="merge-rules-tab" role="tabpanel">
        <div class="card mt-2">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mx-0 row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_filter">
                                <label style="float: left;">Search:
                                    <input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Rule Name</th>
                                    <th class="text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Allow All</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox1">
                                            <label class="custom-control-label" for="checkbox1"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Allow All</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox2">
                                            <label class="custom-control-label" for="checkbox2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Allow All</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox3">
                                            <label class="custom-control-label" for="checkbox3"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Allow All</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox4">
                                            <label class="custom-control-label" for="checkbox4"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mx-0 row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_filter">
                                <label style="float: left;">Search:
                                    <input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Rule Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Top Blocked</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox5">
                                            <label class="custom-control-label" for="checkbox5"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Top Blocked</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox6">
                                            <label class="custom-control-label" for="checkbox6"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Top Blocked</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox7">
                                            <label class="custom-control-label" for="checkbox7"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Top Blocked</td>
                                    <td class="text-right">
                                        <div class="custom-control custom-checkbox"> 
                                            <input class="custom-control-input" type="checkbox" value="" id="checkbox8">
                                            <label class="custom-control-label" for="checkbox8"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- <add-scope></add-scope> -->
    </div>
</template>

<script>
// import AddScope from './AddScope.vue'
export default {
//   components: { AddScope },
    
}
</script>