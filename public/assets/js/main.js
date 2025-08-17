document.addEventListener('DOMContentLoaded', () => {
    // --- NEW: AI Narrative Architect Assistant ---
    const assistant = document.querySelector('.ai-text-assistant');
    if (assistant) {
        const currentTextArea = assistant.querySelector('#description');
        const quickActionButtons = assistant.querySelectorAll('.action-button');
        const customRefineInput = assistant.querySelector('#custom-refine-input');
        const sendRefineButton = assistant.querySelector('#send-refine-request');

        // NEW: Get references to the new elements
        const originalTextContainer = assistant.querySelector('.original-text-container');
        const originalTextContent = assistant.querySelector('.original-text-content');
        const toggleOriginalLink = assistant.querySelector('.toggle-original-link');
        const revertButton = assistant.querySelector('.btn-revert-text');

        let originalText = ''; // Variable to store the text before AI refinement

        const handleRefineRequest = async (prompt) => {
            const currentText = currentTextArea.value;
            if (!currentText.trim()) {
                alert("Please enter some text to refine first.");
                return;
            }

            // --- Store the original text ---
            originalText = currentText;
            originalTextContent.textContent = originalText; // Update the content display
            
            currentTextArea.value = 'AI is refining the text...';
            currentTextArea.disabled = true;
            quickActionButtons.forEach(b => b.disabled = true);
            sendRefineButton.disabled = true;

            try {
                const refinedText = await getAIRefinement(prompt, originalText); // from api.js
                currentTextArea.value = refinedText;
                toggleOriginalLink.style.display = 'inline'; // Show the 'Show Original' link
                originalTextContainer.style.display = 'none'; // Ensure the original text is hidden initially
                toggleOriginalLink.textContent = 'Show Original';

            } catch (error) {
                console.error("Error in handleRefineRequest:", error);
                currentTextArea.value = originalText; // Restore original text on error
                alert("Sorry, there was an error processing your request.");
            } finally {
                currentTextArea.disabled = false;
                quickActionButtons.forEach(b => b.disabled = false);
                sendRefineButton.disabled = false;
            }
        };
        
        // NEW: Event listener for the toggle link
        toggleOriginalLink.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent link from navigating
            const isHidden = originalTextContainer.style.display === 'none';
            originalTextContainer.style.display = isHidden ? 'block' : 'none';
            toggleOriginalLink.textContent = isHidden ? 'Hide Original' : 'Show Original';
        });

        // NEW: Event listener for the revert button
        revertButton.addEventListener('click', () => {
            currentTextArea.value = originalText;
            originalTextContainer.style.display = 'none';
            toggleOriginalLink.textContent = 'Show Original';
        });


        quickActionButtons.forEach(button => {
            button.addEventListener('click', () => {
                const action = button.dataset.action;
                const prompt = `Make the following text more ${action}.`;
                handleRefineRequest(prompt);
            });
        });

        sendRefineButton.addEventListener('click', () => {
            const customPrompt = customRefineInput.value;
            if (!customPrompt.trim()) {
                alert("Please enter a refinement instruction.");
                return;
            }
            handleRefineRequest(customPrompt);
            customRefineInput.value = '';
        });

        customRefineInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendRefineButton.click();
            }
        });
    }


    // NPS Survey Button Interactivity
    const npsButtons = document.querySelectorAll('.score-button');
    npsButtons.forEach(button => {
        button.addEventListener('click', () => {
            npsButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Feature Voting Interactivity
    document.querySelectorAll('.voting-item').forEach(item => {
        const scoreEl = item.querySelector('.vote-score');
        const upvoteBtn = item.querySelector('.vote-arrow.up');
        const downvoteBtn = item.querySelector('.vote-arrow.down');
        
        if (scoreEl && upvoteBtn && downvoteBtn) {
            let score = parseInt(scoreEl.textContent);
            upvoteBtn.addEventListener('click', () => {
                score++;
                scoreEl.textContent = score;
            });
            downvoteBtn.addEventListener('click', () => {
                score--;
                scoreEl.textContent = score;
            });
        }
    });

    // File Uploader Interactivity
    document.querySelectorAll('.file-dropzone-container').forEach(container => {
        const dropzoneLabel = container.querySelector('.file-dropzone');
        const fileInput = container.querySelector('.file-input-hidden');
        const fileListContainer = container.querySelector('.file-list-container');

        if (!dropzoneLabel || !fileInput || !fileListContainer) return;

        dropzoneLabel.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzoneLabel.classList.add('dragover');
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneLabel.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzoneLabel.classList.remove('dragover');
            });
        });
        
        fileInput.addEventListener('change', () => {
            updateFileList(fileInput, fileListContainer);
        });
        dropzoneLabel.addEventListener('drop', (e) => {
            fileInput.files = e.dataTransfer.files;
            updateFileList(fileInput, fileListContainer);
        });
    });

    function updateFileList(fileInput, container) {
        if (fileInput.files.length > 0) {
            let fileListHTML = '<h6>Selected Files:</h6><ul>';
            for (const file of fileInput.files) {
                fileListHTML += `<li>${file.name} (${(file.size / 1024).toFixed(2)} KB)</li>`;
            }
            fileListHTML += '</ul>';
            container.innerHTML = fileListHTML;
        } else {
            container.innerHTML = '';
        }
    }

    // --- Accordion for Service Plan page ---
    const upgradeBtn = document.getElementById('upgrade-plan-btn');
    const accordionPanel = document.getElementById('accordion-panel');

    if (upgradeBtn && accordionPanel) {
        upgradeBtn.addEventListener('click', () => {
            accordionPanel.classList.toggle('open');
        });
    }
});