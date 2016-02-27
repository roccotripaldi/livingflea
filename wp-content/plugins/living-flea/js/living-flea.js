/* global livingFlea, livingFlea.markets, livingFlea.loggedIn, shareAPhoto */
(function($) {
    var galleryIndexes = {},
        filteredMarkets = [],
        selectedMarketIndex = false,
        fbLoginStatus = 'unknown',
        currentPostId = false;

    var LivingFleaTemplate = Backbone.View.extend( {
        tagName: 'div',
        setTemplate: function( template ) {
            this.template = _.template( jQuery( '#flea-template-' + template ).html() );
            return this;
        },
        render: function( data ) {
            this.$el.html( this.template( data ) );
            return this;
        }
    });

    function renderLivingFleaTemplate( templateName, element, data ) {
        var template = new LivingFleaTemplate(),
            templateData = data || {};
        $( element ).html( template.setTemplate( templateName ).render( templateData ).el );
    }

    function appendLivingFleaTemplate( templateName, element, data ) {
        var template = new LivingFleaTemplate(),
            templateData = data || {};
        $( element ).append( template.setTemplate( templateName ).render( templateData ).el );
    }

    var LivingFleaGallery = {

        init: function() {
            $( '.living-flea' ).on( 'click', '.next', function( event ) {
                var data = $( this ).data(),
                    images = $( '#images_' + data.id + ' .gallery-image' ),
                    captions = $( '#slideshow-captions_' + data.id + ' .gallery-caption' ),
                    currentIndex = ( typeof galleryIndexes[ data.id ] === 'undefined' ) ? 0 : galleryIndexes[ data.id ];
                currentIndex++;
                images.addClass( 'hidden' );
                $( images[ currentIndex ] ).removeClass( 'hidden' );
                captions.addClass( 'hidden' );
                $( captions[ currentIndex ] ).removeClass( 'hidden' );
                $( '#slideshow-buttons_' + data.id + ' .prev' ).removeClass( 'hidden' );
                if ( currentIndex === ( data.total - 1 ) ) {
                    $( '#slideshow-buttons_' + data.id + ' .next' ).addClass( 'hidden' );
                }
                galleryIndexes[ data.id ] = currentIndex;
            } );

            $( '.living-flea' ).on( 'click', '.prev', function( event ) {
                var data = $( this ).data(),
                    images = $( '#images_' + data.id + ' .gallery-image' ),
                    captions = $( '#slideshow-captions_' + data.id + ' .gallery-caption' ),
                    currentIndex = galleryIndexes[ data.id ];
                currentIndex--;
                images.addClass( 'hidden' );
                $( images[ currentIndex ] ).removeClass( 'hidden' );
                captions.addClass( 'hidden' );
                $( captions[ currentIndex ] ).removeClass( 'hidden' );
                $( '#slideshow-buttons_' + data.id + ' .next' ).removeClass( 'hidden' );

                if ( currentIndex === 0 ) {
                    $( '#slideshow-buttons_' + data.id + ' .prev' ).addClass( 'hidden' );
                }

                galleryIndexes[ data.id ] = currentIndex;
            } );
        }
    };

    var LivingFleaUploader = {
        init: function() {
            $( '#shaph' ).on( 'click', '.shaph-fb-login', function() {
                $( '#shaph-footer-buttons input' ).prop( 'disabled', true );
                shareAPhoto.App.renderTemplate( '.shaph-facebook-login-status', 'login-loading' );
                LivingFleaLogin.FBGetAuth( LivingFleaUploader.loginCallback );
            } );

            $( '#shaph' ).on( {
                click: function() {
                    selectedMarketIndex = $( this ).data( 'id' );
                    LivingFleaUploader.selectMarket();
                },
                mouseenter: function() {
                    selectedMarketIndex = $( this ).data( 'id' );
                    LivingFleaUploader.setSelectedMarket();
                }
            }, '.filtered-market');

            $( '#shaph' ).on( {
                'keyup': function( event ) {
                    switch( event.keyCode ) {
                        case 13:
                            // enter
                            LivingFleaUploader.selectMarket();
                            break;
                        case 37:
                        case 38:
                            // up left
                            if ( filteredMarkets.length > 1 ) {
                                if ( selectedMarketIndex === 0 ) {
                                    selectedMarketIndex = filteredMarkets.length - 1;
                                } else {
                                    selectedMarketIndex--;
                                }
                                LivingFleaUploader.setSelectedMarket();
                            }
                            break;
                        case 39:
                        case 40:
                            // down right
                            if ( filteredMarkets.length > 1 ) {
                                if ( selectedMarketIndex === filteredMarkets.length - 1 ) {
                                    selectedMarketIndex = 0;
                                } else {
                                    selectedMarketIndex++;
                                }
                                LivingFleaUploader.setSelectedMarket();
                            }
                            break;
                        default:
                            LivingFleaUploader.filterFleaMarkets();
                    }
                }

            }, '#marketName' );
        },

        loginCallback: function( FBResponse ) {
            $("#shaph_access_token").val( FBResponse.authResponse.accessToken );
            $("#shaph_user_id").val( FBResponse.authResponse.userID );
            shareAPhoto.App.finish();
        },

        filterFleaMarkets: function() {
            var filtered = [],
                currentText = $( '#marketName' ).val().toLowerCase();

            if ( currentText.length > 1 ) {
                filtered = livingFlea.markets.filter( function( market ) {
                    return market.toLowerCase().indexOf( currentText ) > -1;
                } );
            }

            filteredMarkets = filtered;

            if ( filtered.length > 0 ) {
                selectedMarketIndex = 0;
                LivingFleaUploader.renderMarketList();
            } else {
                LivingFleaUploader.clearMarketList();
            }
        },

        clearMarketList: function() {
            selectedMarketIndex = false;
            $( '#marketSelect' ).html( '' );
        },

        selectMarket: function() {
            var markets = $( '.filtered-market' );
            $( "#marketName" ).val( $( markets[ selectedMarketIndex ] ).text() );
            LivingFleaUploader.clearMarketList();
        },

        renderMarketList: function() {
            var list = '',
                heading = '<p><em>Choose from known markets:</em></p>';
            filteredMarkets.forEach( function( market, index ) {
                list += '<li><a class="filtered-market" data-id="' + index + '">' + market + '</a></li>';
            } );
            $( '#marketSelect' ).html( heading + '<ul>' + list + '</ul>' );
            LivingFleaUploader.setSelectedMarket();
        },

        setSelectedMarket: function() {
            var markets = $( '.filtered-market' );
            markets.removeClass( 'active' );
            $( markets[ selectedMarketIndex ] ).addClass( 'active' );
        }
    };

    var LivingFleaLogin = {
        init: function() {
            $( 'body' ).on( 'click', '.fb-login', function() {
                LivingFleaLogin.FBGetAuth( LivingFleaLogin.FBLoginCallback );
            } );
            $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
                FB.init({
                    appId: '1038781959488918',
                    version: 'v2.4', // or v2.0, v2.1, v2.2, v2.3
                    cookie: true,
                    xfbml: true,
                    status: true
                });
                FB.getLoginStatus( LivingFleaLogin.FBReceiveStatus );
            });
        },

        FBReceiveStatus: function( response ) {
            $( '.login-prompt' ).on( 'click', LivingFleaLogin.loginPrompt );
            $( '.logout' ).on( 'click', LivingFleaLogin.logout );
            fbLoginStatus = response.status;
        },

        loginPrompt: function( event ) {
            var template = $( event.target ).data( 'template' ),
                element = $( event.target ).data( 'element' );
            shareAPhoto.App.scrollY = jQuery( window ).scrollTop();
            jQuery( '#shaph' ).addClass( 'open' );
            renderLivingFleaTemplate( template, element );
            shareAPhoto.App.setContentHeight();
        },

        logout: function() {
            renderLivingFleaTemplate( 'logout', '.fb-logout-status' );
            $.post(
                livingFlea.ajax_url,
                {
                    action: 'livingflea_fblogout'
                },
                function () {
                    location.reload();
                }
            );
        },

        login: function( FBResponse, callback ) {
            $.post(
                livingFlea.ajax_url,
                {
                    auth_nonce: livingFlea.nonce,
                    access_token: FBResponse.authResponse.accessToken,
                    user_id: FBResponse.authResponse.userID,
                    action: 'livingflea_fblogin'
                },
                callback,
                'json'
            );
        },

        FBLoginCallback: function( FBresponse ) {
            LivingFleaLogin.login( FBresponse, function() { location.reload() } );
        },

        FBGetAuth: function ( callback ) {
            renderLivingFleaTemplate( 'loading', '.facebook-login-status' );
            shareAPhoto.App.setContentHeight();
            if ( fbLoginStatus === 'connected' ) {
                FB.getLoginStatus( callback );
            } else {
                FB.login( callback, { scope: 'public_profile,email' } );
            }
        }
    };

    var LivingFleaMenu = {
        init: function() {
            $( '#hamburger' ).toggle(
                function() {
                    $( '#nav-items' ).css( 'display', 'block' );
                },

                function() {
                    $( '#nav-items' ).css( 'display', 'none' );
                }
            )
        }
    };

    var LivingFleaComments = {
        redirectAfterLogin: false,

        init: function() {

            $( 'body' ).on( 'click', '.fb-login-comment', function( event ) {
                LivingFleaLogin.FBGetAuth( LivingFleaComments.loginCallback );
            } );

            $('.living-flea').on('click', '.comment-login-button', function ( event ) {
                LivingFleaComments.redirectAfterLogin = $( event.target ).data( 'postname' );
                LivingFleaLogin.loginPrompt( event );
            });

            if ( livingFlea.loggedIn ) {
                $('.living-flea').on('keyup', '.post-comment-input', function ( event ) {
                    if (event.keyCode === 13) {
                        var postId = $(this).data('id'),
                            comment = $(this).val();
                        LivingFleaComments.postComment(postId, comment);
                    }
                });
            }

        },

        loginCallback: function( FBResponse ) {
            LivingFleaLogin.login( FBResponse, function() {
                window.location = 'http://livingflea.com/' + LivingFleaComments.redirectAfterLogin + '#comments';
            } );
        },

        postComment: function( postId, comment ) {
            renderLivingFleaTemplate( 'comment-loading', '#comment-form-' + postId );
            $.post(
                livingFlea.ajax_url,
                {
                    action: 'livingflea_comment',
                    post_id: postId,
                    comment_content: comment
                },
                function ( response ) {
                    renderLivingFleaTemplate( 'comment-form', '#comment-form-' + postId, { postId: postId } );
                    appendLivingFleaTemplate( 'comment', '#post-comments-' + postId, response );
                    $( "#post-comments-" + postId ).removeClass( 'empty' );
                },
                'json'
            );
        }
    };

    $( document ).ready( function() {
        LivingFleaGallery.init();
        LivingFleaUploader.init();
        LivingFleaMenu.init();
        LivingFleaLogin.init();
        LivingFleaComments.init();
    } );

})(jQuery);
