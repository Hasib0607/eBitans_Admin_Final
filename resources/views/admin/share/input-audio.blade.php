<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all elements with audio-trigger class
        const audioFields = document.querySelectorAll('.audio-trigger');
        const audioFieldFocus = document.querySelectorAll('.audio-trigger-focus');
        let currentAudio = null;
        // Language configuration
        const languageConfig = {
            // 'en-US': {name: 'English (US)', defaultVoice: 'Microsoft David'},
            'en-US': {name: 'English (US)', defaultVoice: 'Microsoft Zira'},
            'bn-BD': {name: 'Bengali (Bangladesh)', defaultVoice: 'Google বাংলা'},
            'hi-IN': {name: 'Hindi (India)', defaultVoice: 'Google हिन्दी'},
            'ar-SA': {name: 'Arabic (Saudi Arabia)', defaultVoice: 'Google عربي'}
        };

        // Add event listeners to each field
        audioFields.forEach(field => {
            // Play audio when field gets focus
            field.addEventListener('click', function () {
                // Stop any currently playing audio
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                }

                // Get the audio element ID from data attribute
                const audioSrc = this.getAttribute('data-audio');
                if (audioSrc) {
                    currentAudio = new Audio(audioSrc); // create new Audio object
                }

                // Play the audio
                if (currentAudio) {
                    currentAudio.play().catch(e => {
                        // console.log("Audio play failed:", e);
                    });

                    return false;
                }

                const fieldName = this.previousElementSibling.textContent;
                const textToSpeech = `Please enter your ${fieldName}`;
                const speech = this.getAttribute('data-speech') || textToSpeech;
                speak(speech, currentAudio);
            });
        });

        // Add event listeners to each field
        audioFieldFocus.forEach(field => {
            // Play audio when field gets focus
            field.addEventListener('mouseover', function () {
                // Stop any currently playing audio
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                }

                // Get the audio element ID from data attribute
                const audioSrc = this.getAttribute('data-audio');
                currentAudio = new Audio(audioSrc); // create new Audio object

                // Play the audio
                if (currentAudio) {
                    currentAudio.play().catch(e => {
                        // console.log("Audio play failed:", e);
                    });
                }

                const fieldName = this.previousElementSibling.textContent;
                const textToSpeech = `Please enter your ${fieldName}`;
                const speech = this.getAttribute('data-speech') || textToSpeech;
                // speak(speech, currentAudio);
            });
        });

        function speak(text, audio = false) {
            // Check if speech synthesis is supported
            if (!('speechSynthesis' in window)) {
                // console.log("Text-to-speech not supported in this browser");

                if (audio) {
                    audio.play().catch(e => {
                        // console.log("Audio play failed:", e);
                    });
                }

                return false;
            }

            // Cancel any ongoing speech
            window.speechSynthesis.cancel();
            // Create new utterance
            const utterance = new SpeechSynthesisUtterance(text);

            // Mobile-specific optimizations
            utterance.rate = 0.9; // Slightly slower for mobile clarity
            utterance.volume = 1; // Max volume (still respects device volume)

            const lang = 'en-US';
            // Set language
            utterance.lang = lang;

            function loadVoices() {
                return new Promise((resolve) => {
                    let voices = window.speechSynthesis.getVoices();
                    if (voices.length) {
                        resolve(voices);
                    } else {
                        window.speechSynthesis.onvoiceschanged = () => {
                            voices = window.speechSynthesis.getVoices();
                            resolve(voices);
                        };
                    }
                });
            }

            loadVoices().then((voices) => {
                const selectedVoice = voices.find(voice =>
                    voice.lang === lang &&
                    voice.name.includes(languageConfig[lang].defaultVoice)
                );

                if (selectedVoice) {
                    utterance.voice = selectedVoice;
                } else {
                    // console.log('No voice found for language:', lang);
                }

                // Handle mobile-specific events
                utterance.onerror = function (event) {
                    // console.log('Speech error:', event.error);
                };

                utterance.onend = function () {
                    // console.log('Speech finished');
                };

                // Speak the text
                window.speechSynthesis.speak(utterance);

                return false;
            });

            // console.log('No voice found for this language');
        }
    });
</script>

