/**
 * Public JavaScript for the Mastery Box Direct plugin
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        initializeGame();
        addInteractiveEffects();
    });

    function initializeGame() {
        $('.mastery-box').on('click', function() {
            var $box = $(this);
            if ($box.hasClass('disabled') || $box.hasClass('flipped')) {
                return;
            }
            $('.mastery-box').addClass('disabled');
            var boxNumber = $box.data('box');
            playGame(boxNumber, $box);
        });

        $(document).on('click', '#play-again-btn', function() {
            // Reload the page to play again
            window.location.reload();
        });
    }

    function playGame(boxNumber, $clickedBox) {
        $clickedBox.find('.box-content').html('<span class="loading"></span>');
        $clickedBox.addClass('flipped');

        var gameData = {
            action: 'mastery_box_direct_play_game',
            nonce: mastery_box_direct_ajax.nonce,
            box: boxNumber
        };

        $.ajax({
            url: mastery_box_direct_ajax.ajax_url,
            type: 'POST',
            data: gameData,
            success: function(response) {
                if (response && response.success && response.data) {
                    handleGameResult(response.data, $clickedBox);
                } else {
                    handleGameError((response && response.data) ? response.data : 'Game error occurred.');
                }
            },
            error: function() {
                handleGameError('Network error. Please try again.');
            }
        });
    }

    function handleGameResult(result, $clickedBox) {
        if (result.is_winner) {
            $clickedBox.find('.box-content').html(
                '<div class="winner-content">🎉 <strong>' +
                escapeHtml(result.gift_name || 'Winner!') + '</strong></div>'
            );
            $clickedBox.addClass('winner-animation');
            addConfettiEffect();
        } else {
            $clickedBox.find('.box-content').html(
                '<div class="loser-content">😔 <strong>Try Again</strong></div>'
            );
        }

        setTimeout(function() {
            redirectToResultPage();
        }, 2000);
    }

    
    function redirectToResultPage() {
        var url = (typeof mastery_box_direct_ajax !== 'undefined' && mastery_box_direct_ajax.result_page_url)
            ? mastery_box_direct_ajax.result_page_url
            : '/game-result/';
        if (!url) {
            // Fallbacks
            var candidates = ['/game-result/', '/result/', '/results/', '/direct-result/'];
            url = candidates[0];
        }
        // Bust cache and go
        var sep = url.indexOf('?') === -1 ? '?' : '&';
        window.location.href = url + sep + 't=' + Date.now();
    }
function showGameResultInline() {
        var $resultDiv = $('#mastery-box-result');
        var $playAgainBtn = $('#play-again-btn');

        if ($resultDiv.length) {
            // Results will be shown by redirecting to result shortcode page
            // For now, show play again button
            $resultDiv.fadeIn();
            $playAgainBtn.fadeIn();
        }
    }

    function handleGameError(errorMessage) {
        $('.mastery-box').removeClass('disabled');
        alert('Error: ' + errorMessage);
    }

    function addConfettiEffect() {
        var colors = ['#f43f5e', '#06b6d4', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444'];
        var confettiCount = 50;
        for (var i = 0; i < confettiCount; i++) {
            (function(iIndex) {
                setTimeout(function() {
                    createConfettiPiece(colors[Math.floor(Math.random() * colors.length)]);
                }, iIndex * 50);
            })(i);
        }
    }

    function createConfettiPiece(color) {
        var $confetti = $('<div class="confetti"></div>');
        $confetti.css({
            position: 'fixed',
            left: Math.random() * 100 + '%',
            top: '-10px',
            width: '10px',
            height: '10px',
            backgroundColor: color,
            zIndex: 9999,
            pointerEvents: 'none'
        });
        $('body').append($confetti);
        $confetti.animate({
            top: $(window).height() + 20,
            left: (Math.random() - 0.5) * 200 + parseInt($confetti.css('left'), 10)
        }, {
            duration: 3000 + Math.random() * 2000,
            easing: 'linear',
            complete: function() {
                $confetti.remove();
            }
        });
        var rotation = 0;
        var rotateInterval = setInterval(function() {
            rotation += 10;
            $confetti.css('transform', 'rotate(' + rotation + 'deg)');
            if (!$confetti.parent().length) {
                clearInterval(rotateInterval);
            }
        }, 50);
    }

    function escapeHtml(text) {
        if (typeof text !== 'string') return '';
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function addInteractiveEffects() {
        $('.mastery-box').on('mouseenter', function() {
            $(this).addClass('hover-effect');
        }).on('mouseleave', function() {
            $(this).removeClass('hover-effect');
        });

        $(document).on('keydown', function(e) {
            if (e.key >= '1' && e.key <= '9') {
                var boxNumber = parseInt(e.key, 10);
                var $box = $('.mastery-box[data-box="' + boxNumber + '"]');
                if ($box.length && !$box.hasClass('disabled') && !$box.hasClass('flipped')) {
                    $box.trigger('click');
                }
            }
        });
    }

})(jQuery);