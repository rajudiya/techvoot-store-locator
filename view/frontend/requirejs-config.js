var config = {
    map: {
        '*': {
            'techvoot-storelocator-opening-hours': 'Techvoot_StoreLocator/js/retailer/opening-hours',
            'techvoot-storelocator-map': 'Techvoot_StoreLocator/js/retailer/store-map',
            'techvoot-storelocator-store': 'Techvoot_StoreLocator/js/model/store',
            'techvoot-storelocator-store-collection': 'Techvoot_StoreLocator/js/model/stores',
            'techvoot-storelocator-store-schedule': 'Techvoot_StoreLocator/js/model/store/schedule'
        }
    },
    config: {
        mixins: {
            'Techvoot_Map/js/map': {
                'Techvoot_StoreLocator/js/map-mixin': true
            }
        }
    }
};
