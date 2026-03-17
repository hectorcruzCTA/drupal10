(function ($) {

  Drupal.behaviors.udg_media = {
    attach: function (context, settings) {
      $('#social-stream').dcSocialStream({
       feeds: {
          /*twitter: {
            id: '/9927875,#designchemical,designchemical',
            thumb: true
          },*/
          twitter: {
            id: 'udg_oficial',
            thumb: true,
          },
          /*rss: {
            id: 'http://feeds.feedburner.com/DesignChemical,http://feeds.feedburner.com/codecondo'
          },*/
          /*stumbleupon: {
            id: 'remix4'
          },*/
          /*facebook: {
            id: '157969574262873,Facebook Timeline/376995711728'
          },*/
          facebook: {
            id: '157969574262873'
          },
          /*google: {
            id: '111470071138275408587'
          },*/
          /*delicious: {
            id: 'designchemical'
          },*/
         /* vimeo: {
            id: 'brad',
            thumb: 'medium'
          },*/
          youtube: {
            id: 'UC8m5IpxWBMTxawT5wdNp7lA',
            out: 'intro,thumb,title,user,share',
            thumb: 'medium',
            api_key: 'AIzaSyBd_-IhST8Ddc3lFUubKBSDoeb8y1JII1A',
          },
          /*pinterest: {
            id: 'jaffrey,designchemical/design-ideas'
          },*/
          /*flickr: {
            id: ''
          },*/
          /*lastfm: {
            id: 'lastfm'
          },*/
          /*dribbble: {
            id: 'frogandcode'
          },*/
          /*deviantart: {
            id: 'isacg'
          },*/
          /*tumblr: {
            id: 'richters',
            thumb: 250
          }*/
        },
        rotate: {
          delay: 0
        },
        twitterId: 'udg_oficial',
        control: false,
        filter: false,
        wall: true,
        max: 'limit',
        limit: 5,
        iconPath: '/libraries/media/images/dcsns-dark/',
        imagePath: '/libraries/media/images/dcsns-dark/'
      });
    }
  }
}(jQuery));
