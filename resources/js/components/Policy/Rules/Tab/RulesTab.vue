<template>
    <div class="tab-pane active" id="rules" aria-labelledby="rules-tab" role="tabpanel">
        <div class="card mt-2">
            <div class="d-flex justify-content-between align-items-center mx-0 row">
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_filter">
                        <label style="float: left;">Search:
                            <input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0">
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <a href="rule-builder" style="float: right;" class="btn btn-primary">Add Rule</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rule Nmae</th>
                            <th>Action</th>
                            <th>Exit on First Match</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Rule Nmae</td>
                            <td>Allow</td>
                            <td>True</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>Rule Nmae</td>
                            <td>Allow</td>
                            <td>True</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>Rule Nmae</td>
                            <td>Allow</td>
                            <td>True</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>Rule Nmae</td>
                            <td>Allow</td>
                            <td>True</td>
                            <td class="text-center">
                                <i data-feather="edit"></i>
                                <i class="ml-1" data-feather="delete"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <add-rule></add-rule> -->
    </div>
</template>
<script>
// import AddRule from './AddRule.vue'
export default {
//   components: { AddRule },
    
}
</script>