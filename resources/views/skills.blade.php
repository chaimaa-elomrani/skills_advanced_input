<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Skills Input Form</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tag-container::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .tag-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 2px;
        }

        .tag-container::-webkit-scrollbar-track {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans p-8">
    <div class="container mx-auto max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form id="skills-form">
                <!-- Skills -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="skills-input" class="block text-sm font-medium text-gray-700">Skills
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="text-xs text-gray-500 mr-1">Add your top skills</span>
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-500 rounded-full cursor-pointer"
                                title="Add skills that showcase your expertise. These will help others find you for relevant projects.">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="relative flex-grow">
                            <input type="text" id="skills-input"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Type a skill and press Enter (e.g., JavaScript, Project Management)">
                        </div>
                        <button type="button" id="add-skill-btn"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Add
                        </button>
                    </div>
                    <div id="skills-container"
                        class="flex flex-wrap gap-2 min-h-[40px] max-h-[120px] overflow-y-auto p-2 border border-gray-200 rounded-lg tag-container">
                        <!-- Skills tags will be added here dynamically -->
                    </div>
                    <div class="hidden mt-1 text-sm text-red-500" id="skills-error"></div>
                    <input type="hidden" name="skills" id="skills-hidden">
                </div>

                <div class="mt-6">
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Save Skills
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
             
            // const skillsStore = {
            //     items:[],
            //     nextId: 1,

            //     addSkill(name){
            //         const skill = {
            //             id: this.nextId++,
            //             name: name,
            //         };
            //         this.items.push(skills);
            //         return skill;   
            //     },

            // }
            // Skills input
            const skillsInput = document.getElementById('skills-input');
            const addSkillBtn = document.getElementById('add-skill-btn');
            const skillsContainer = document.getElementById('skills-container');
            const skillsForm = document.getElementById('skills-form');
            const skillsError = document.getElementById('skills-error');
            const skillsHidden = document.getElementById('skills-hidden');
            let skillsData = [];
            let nextId = 1;



            function addSkill(skillName) {
                if (!skillName.trim()) return;

                // Check if skill already exists
                const existingSkills = Array.from(skillsContainer.querySelectorAll('.skill-tag')).map(tag =>
                    tag.querySelector('span').textContent.toLowerCase()
                );

                if (existingSkills.includes(skillName.toLowerCase())) return;

                // Create skill tag
                const skillTag = document.createElement('div');
                skillTag.className = 'skill-tag inline-flex items-center bg-blue-100 text-blue-700 rounded-full px-3 py-1 text-sm';
                skillTag.innerHTML = `
                    <span>${skillName}</span>
                    <button type="button" class="ml-1 text-blue-500 hover:text-blue-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;  
                const skillId = nextId++;
                skillsData.push({ id: skillId, name: skillName });

                // Add remove event listener
                skillTag.querySelector('button').addEventListener('click', function() {
                    skillTag.remove();
                    updateHiddenField();
                });

                skillsContainer.appendChild(skillTag);
                skillsInput.value = '';
                updateHiddenField();
            }

            function updateHiddenField() {
                const skills = Array.from(skillsContainer.querySelectorAll('.skill-tag')).map(tag =>
                    tag.querySelector('span').textContent
                );
                skillsHidden.value = JSON.stringify(skills);
            }

            addSkillBtn.addEventListener('click', function() {
                addSkill(skillsInput.value);
            });

            skillsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSkill(this.value);
                }
            });

            skillsForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate that at least one skill is added
                const skillTags = skillsContainer.querySelectorAll('.skill-tag');
                
                if (skillTags.length === 0) {
                    skillsError.textContent = 'Please add at least one skill';
                    skillsError.classList.remove('hidden');
                    return;
                } else {
                    skillsError.classList.add('hidden');
                }
                
                // Get all skills
                const skills = Array.from(skillTags).map(tag => 
                    tag.querySelector('span').textContent
                );
                
                // Send data to server
                sendSkills(skills);
            });

            async function sendSkills(skills) {
                try {
                    // Show loading indicator if you have one
                    // loader.style.display = 'flex';
                    
                    // Check if CSRF token exists
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('CSRF token not found. Please refresh the page.');
                        return;
                    }
                    
                    console.log('Sending skills:', skills);
                    console.log('CSRF token:', csrfToken.getAttribute('content'));
                    
                    const response = await fetch('/save-skills', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            skills: JSON.stringify(skills)
                        })
                    });
                    
                    console.log('Response status:', response.status);
                    
                    // Try to get the response text first to debug
                    const responseText = await response.text();
                    console.log('Response text:', responseText);
                    
                    // Parse the JSON if possible
                    let result;
                    try {
                        result = JSON.parse(responseText);
                        console.log('Parsed result:', result);
                    } catch (e) {
                        console.error('Could not parse JSON response:', e);
                        alert('Server returned invalid JSON. Check console for details.');
                        return;
                    }
                    
                    if (result.success) {
                        alert('Skills saved successfully!');
                    } else {
                        alert('Error saving skills: ' + (result.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('An error occurred while saving skills: ' + error.message);
                } finally {
                    // Hide loading indicator if you have one
                    // loader.style.display = 'none';
                }
            }        // async function sendSkills(skills){
        //     loader.style.display = 'flex';

        // }
        });
    
    </script>
</body>
</html>