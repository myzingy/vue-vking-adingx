export default {
    //ads
    getCampaignsData:{act:'user.getCampaignsInsightsData',code:10000},
    getAdsetsData:{act:'user.getAdsetsInsightsData',code:10001},
    getAdsData:{act:'user.getAdsInsightsData',code:10002},
    getFBAccounts:{act:'user.getFBAccounts',code:10003},
    addAccounts:{act:'user.addAccounts',code:10004},
    delAccounts:{act:'user.delAccounts',code:10005},

    //rules
    getRulesData:{act:'user.getRulesData',code:11000},
    updateRulesData:{act:'user.updateRulesData',code:11001},
    getRulesLog:{act:'user.getRulesLog',code:11002},
    getRulesForAd:{act:'user.getRulesForAd',code:11003},
    saveRulesForAd:{act:'user.saveRulesForAd',code:11004},

    //common
    getAcsList:{act:'user.getAcsList',code:12000},
    getNavList:{act:'user.getNavList',code:12001},


    //user
    login:{act:'login',code:13000},
    getUsers:{act:'user.getUsers',code:13001},
    delUsers:{act:'user.delUsers',code:13002},
    addUsers:{act:'user.addUsers',code:13003},
    updateUsers:{act:'user.updateUsers',code:13004},
    getAccountsForEmail:{act:'user.getAccountsForEmail',code:13005},
    setAccountsForEmail:{act:'user.setAccountsForEmail',code:13006},
}