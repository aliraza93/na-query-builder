<template>
    <div>
        <div class="row">
            <!-- Tabs with Icon starts -->
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rules Details</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="rules-tab" data-toggle="tab" href="#rules" aria-controls="rules" role="tab" aria-selected="true"><i data-feather="folder"></i> Rules</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="scope-tab" data-toggle="tab" href="#merge-rules" aria-controls="merge-rules" role="tab" aria-selected="false"><i data-feather="users"></i> Merge Rules</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <rules-tab></rules-tab>
                            <merge-rules></merge-rules>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs with Icon ends -->
        </div>
    </div>
</template>

<script>
import MergeRules from './Tab/MergeRules.vue'
import RulesTab from './Tab/RulesTab.vue'

export default {
  components: { RulesTab, MergeRules },
    props: ['user']
}
</script>