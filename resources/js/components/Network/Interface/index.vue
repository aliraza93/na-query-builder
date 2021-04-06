<template>
    <div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <ul class="nav nav-tabs" role="tablist" style="justify-content: center;">
                    <li class="nav-item">
                        <a class="nav-link active" id="interfaces-tab" data-toggle="tab" href="#interfaces" aria-controls="interfaces" role="tab" aria-selected="true"><i data-feather="folder"></i> Interfaces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dns-tab" data-toggle="tab" href="#dns" aria-controls="dns" role="tab" aria-selected="false"><i data-feather="tool"></i> DNS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="bridges-tab" data-toggle="tab" href="#bridges" aria-controls="bridges" role="tab" aria-selected="false"><i data-feather="users"></i> Bridges</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <!-- Tabs with Icon starts -->
            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="tab-content">
                    <interfaces-tab></interfaces-tab>
                    <dns-tab></dns-tab>
                    <bridges-tab></bridges-tab>
                </div>    
            </div>
            <!-- Tabs with Icon ends -->
            <div class="col-md-2"></div>
        </div>
    </div>
</template>

<script>
import BridgesTab from './Tabs/BridgesTab.vue';
import DnsTab from './Tabs/DnsTab.vue';
import InterfacesTab from './Tabs/InterfacesTab.vue';

export default {
    components: {
        InterfacesTab,
        DnsTab,
        BridgesTab
    },
 
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