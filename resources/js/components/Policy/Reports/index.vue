<template>
  <div>
    <vue-query-builder :rules="rules" :colors="colors" :labels="labels" :operators="operators" :operands="operands"></vue-query-builder>
  </div>
</template>

<script>
import VueQueryBuilder from 'vue-query-builder';

export default {
  components: { VueQueryBuilder },
  data() {
    return {
      query: null,

        rules: [
            {
                type: "text",
                id: "vegetable",
                label: "Vegetable",
            },
            {
                type: "radio",
                id: "fruit",
                label: "Fruit",
                choices: [
                    {label: "Apple", value: "apple"},
                    {label: "Banana", value: "banana"}
                ]
            },
        ],

        colors: [
            'hsl(88, 50%, 55%)',
            'hsl(187, 100%, 45%)',
            'hsl(15, 100%, 55%)',
        ],

        labels: {
            "matchType": "IF",
            "matchTypes": [
                {"id": "and", "label": "And"},
                {"id": "or", "label": "OR"}
            ],
            "addRule": "Add Rule",
            "removeRule": "&times;",
            "addGroup": "Add Group",
            "removeGroup": "&times;",
            "textInputPlaceholder": "value",
        },

        operators: [
            { text: '=', value: '$eq' },
            { text: '!=', value: '$ne' },
            { text: '>', value: '$gt' },
            { text: '<', value: '$lt', disabled: true },
            { text: 'LIKE', value: '$like' },
            { text: 'NULL', value: '$null' },
            { text: 'NOT NULL', value: '$not_null' },
        ],

        operands: [
            { text: 'ID', value: 'id', type: 'number', disabled: true },
            { text: 'Manager ID', value: 'admin_id', type: 'number' },
            { text: 'Administrator name', value: 'admin_name', type: 'string' },
            { text: 'Resource',
                value: 'resource',
                type: 'string',
                values: [
                { text: 'Contents', value: 'contents' },
                { text: 'Content options', value: 'contents_options', disabled: true },
                { text: 'Content option item', value: 'contents_options_items' },
                { text: 'Order', value: 'order' },
                ] },
            { text: 'Action',
                value: 'action',
                type: 'string',
                values: [
                { text: 'Start synchronization', value: 'start_sync_list_item' },
                { text: 'Synchronization end', value: 'end_sync_list_item' },
                { text: 'List synchronization', value: 'sync_list_item' },
                { text: 'Delete non-existent items', value: 'delete_non_exist_items' },
                { text: 'Content mismatch', value: 'different_item' },
                { text: 'Full sync', value: 'sync_entire_item' },
                { text: 'Single content synchronization', value: 'sync_single_item' },
                { text: 'Incremental synchronization', value: 'sync_incremental_item' },
                { text: 'Code retransmission', value: 'resend_code' },
                { text: 'Resend Notification Talk', value: 'resend_notice' },
                ] },
            { text: 'BOOL',
                value: 'bool_test',
                type: 'boolean',
                values: [
                { text: 'True', value: true },
                { text: 'False', value: false },
                { text: 'Must fail', value: 'true1' },
                ] },
            { text: 'DATE', value: 'date_test', type: 'date' },
            { text: 'DATETIME', value: 'datetime_test', type: 'datetime' },
        ]
    };
  }
};
</script>
