<template>
  <div class="app">
    <div class="header">
      <h1>SAAS Entry Level Product</h1>
      <img src="https://static.danads.com/content/uploads/2020/07/da_logo-r-mono-green.png" alt="Logo" class="logo" />
    </div>
    <div class="input-section">
      <input
        type="url"
        placeholder="Enter your link here..."
        v-model="linkInput"
        class="link-input"
      />
      <button @click="processStep1" class="action-btn" :disabled="isGenerating">
        {{ isGenerating ? 'Generating...' : 'Generate' }}
      </button>
    </div>

    <div v-if="isGenerating || previewData || errorMessage" class="preview">
      <h2 class="preview-title">Preview 1 - Step 1 Results</h2>
      <div v-if="errorMessage" class="error">
        {{ errorMessage }}
      </div>
      <div v-else-if="isGenerating" class="loading-info">
        <div class="request-info">
          <div class="endpoint">POST /api/step1</div>
          <div class="payload">
            <div class="payload-label">Request payload:</div>
            <div class="payload-data">
              <pre>{{ JSON.stringify({ url: linkInput }, null, 2) }}</pre>
            </div>
          </div>
          <div class="timer">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12,6 12,12 16,14"></polyline>
            </svg>
            {{ elapsedTime }}s elapsed...
          </div>
        </div>
      </div>
      <div v-else-if="previewData" class="json-output">
        <div class="json-controls">
          <button @click="toggleEditMode" class="edit-btn">
            {{ isEditMode ? 'View JSON' : 'Edit JSON' }}
          </button>
          <button v-if="!isEditMode" @click="processStep2" class="submit-btn" :disabled="isGenerating2">
            {{ isGenerating2 ? 'Processing...' : 'Submit to Step 2' }}
          </button>
        </div>
        <div v-if="!isEditMode">
          <pre class="json-display">{{ JSON.stringify(previewData, null, 2) }}</pre>
        </div>
        <div v-else class="json-editor">
          <textarea
            v-model="editableJson"
            class="json-textarea"
            @blur="updateJsonFromText"
            placeholder="Edit JSON..."
          ></textarea>
          <div class="editor-actions">
            <button @click="updateJsonFromText" class="save-btn">Apply Changes</button>
            <button @click="resetJson" class="reset-btn">Reset</button>
          </div>
        </div>
        <div class="loading-time">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12,6 12,12 16,14"></polyline>
          </svg>
          Loaded in {{ loadingTime }}ms
        </div>
      </div>
    </div>

    <div v-if="isGenerating2 || previewData2 || errorMessage2" class="preview">
      <h2 class="preview-title">Preview 2 - Step 2 Results</h2>
      <div v-if="errorMessage2" class="error">
        {{ errorMessage2 }}
      </div>
      <div v-else-if="isGenerating2" class="loading-info">
        <div class="request-info">
          <div class="endpoint">POST /api/step2</div>
          <div class="payload">
            <div class="payload-label">Request payload:</div>
            <div class="payload-data">
              <pre>{{ JSON.stringify(previewData, null, 2) }}</pre>
            </div>
          </div>
          <div class="timer">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12,6 12,12 16,14"></polyline>
            </svg>
            {{ elapsedTime2 }}s elapsed...
          </div>
        </div>
      </div>
      <div v-else-if="previewData2" class="json-output">
        <div class="json-controls">
          <button @click="toggleEditMode2" class="edit-btn">
            {{ isEditMode2 ? 'View JSON' : 'Edit JSON' }}
          </button>
        </div>
        <div v-if="!isEditMode2">
          <pre class="json-display">{{ JSON.stringify(previewData2, null, 2) }}</pre>
        </div>
        <div v-else class="json-editor">
          <textarea
            v-model="editableJson2"
            class="json-textarea"
            @blur="updateJsonFromText2"
            placeholder="Edit JSON..."
          ></textarea>
          <div class="editor-actions">
            <button @click="updateJsonFromText2" class="save-btn">Apply Changes</button>
            <button @click="resetJson2" class="reset-btn">Reset</button>
          </div>
        </div>
        <div class="loading-time">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12,6 12,12 16,14"></polyline>
          </svg>
          Loaded in {{ loadingTime2 }}ms
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const linkInput = ref('https://www.brompton.com/p/817/c-line-explore-23')
const isGenerating = ref(false)
const previewData = ref(null)
const errorMessage = ref('')
const loadingTime = ref(0)
const elapsedTime = ref(0)
const isEditMode = ref(false)
const editableJson = ref('')
const originalData = ref(null)

// Step 2 state variables
const isGenerating2 = ref(false)
const previewData2 = ref(null)
const errorMessage2 = ref('')
const loadingTime2 = ref(0)
const elapsedTime2 = ref(0)
const isEditMode2 = ref(false)
const editableJson2 = ref('')
const originalData2 = ref(null)

const toggleEditMode = () => {
  if (!isEditMode.value) {
    editableJson.value = JSON.stringify(previewData.value, null, 2)
    originalData.value = JSON.parse(JSON.stringify(previewData.value))
  }
  isEditMode.value = !isEditMode.value
}

const updateJsonFromText = () => {
  try {
    previewData.value = JSON.parse(editableJson.value)
  } catch (error) {
    alert('Invalid JSON format')
  }
}

const resetJson = () => {
  previewData.value = originalData.value
  editableJson.value = JSON.stringify(originalData.value, null, 2)
}

// Step 2 functions
const toggleEditMode2 = () => {
  if (!isEditMode2.value) {
    editableJson2.value = JSON.stringify(previewData2.value, null, 2)
    originalData2.value = JSON.parse(JSON.stringify(previewData2.value))
  }
  isEditMode2.value = !isEditMode2.value
}

const updateJsonFromText2 = () => {
  try {
    previewData2.value = JSON.parse(editableJson2.value)
  } catch (error) {
    alert('Invalid JSON format')
  }
}

const resetJson2 = () => {
  previewData2.value = originalData2.value
  editableJson2.value = JSON.stringify(originalData2.value, null, 2)
}

const processStep1 = async () => {
  if (linkInput.value && !isGenerating.value) {
    isGenerating.value = true
    errorMessage.value = ''
    previewData.value = null
    loadingTime.value = 0
    elapsedTime.value = 0

    const startTime = performance.now()

    // Timer to update elapsed time every 100ms
    const timer = setInterval(() => {
      const currentTime = performance.now()
      elapsedTime.value = parseFloat(((currentTime - startTime) / 1000).toFixed(1))
    }, 100)

    try {
      const response = await axios.post('/api/step1', {
        url: linkInput.value
      })

      const endTime = performance.now()
      loadingTime.value = Math.round(endTime - startTime)
      previewData.value = response.data
    } catch (error) {
      errorMessage.value = error.response?.data?.message || 'An error occurred'
      console.error('Error:', error)
    } finally {
      clearInterval(timer)
      isGenerating.value = false
    }
  }
}

const processStep2 = async () => {
  if (previewData.value && !isGenerating2.value) {
    isGenerating2.value = true
    errorMessage2.value = ''
    previewData2.value = null
    loadingTime2.value = 0
    elapsedTime2.value = 0

    const startTime = performance.now()

    // Timer to update elapsed time every 100ms
    const timer = setInterval(() => {
      const currentTime = performance.now()
      elapsedTime2.value = parseFloat(((currentTime - startTime) / 1000).toFixed(1))
    }, 100)

    try {
      const response = await axios.post('/api/step2', previewData.value)

      const endTime = performance.now()
      loadingTime2.value = Math.round(endTime - startTime)
      previewData2.value = response.data
    } catch (error) {
      errorMessage2.value = error.response?.data?.message || 'An error occurred'
      console.error('Error:', error)
    } finally {
      clearInterval(timer)
      isGenerating2.value = false
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body, html {
  margin: 0;
  padding: 0;
  background: #0d1117;
}
.app {
  width: 100vw;
  height: 100vh;
  padding: 1rem;
  font-family: 'Courier New', Monaco, Consolas, monospace;
  background: #0d1117;
  color: #c9d1d9;
  box-sizing: border-box;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.logo {
  height: 32px;
  width: auto;
}

h1 {
  color: #58a6ff;
  font-size: 1.2rem;
  font-weight: normal;
  margin: 0;
}

h1::before {
  content: '> ';
  color: #7c3aed;
}

.input-section {
  background: #161b22;
  padding: 1rem;
  border: 1px solid #30363d;
  border-radius: 3px;
}

.link-input {
  width: 100%;
  padding: 0.5rem;
  background: #0d1117;
  border: 1px solid #30363d;
  color: #c9d1d9;
  font-family: inherit;
  font-size: 0.9rem;
  margin-bottom: 0.75rem;
  box-sizing: border-box;
}

.link-input:focus {
  outline: none;
  border-color: #58a6ff;
  background: #010409;
}

.link-input::placeholder {
  color: #6e7681;
}

.action-btn {
  padding: 0.5rem 1rem;
  background: #21262d;
  color: #c9d1d9;
  border: 1px solid #30363d;
  cursor: pointer;
  font-family: inherit;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.action-btn:hover {
  background: #30363d;
  border-color: #58a6ff;
}

.action-btn::before {
  content: '$ ';
  color: #7c3aed;
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.action-btn:disabled:hover {
  background: #21262d;
  border-color: #30363d;
}

.preview {
  margin-top: 1.5rem;
  padding: 1rem;
  background: #161b22;
  border: 1px solid #30363d;
  border-radius: 3px;
  font-family: inherit;
  min-height: 200px;
}

.preview-title {
  color: #58a6ff;
  font-size: 1rem;
  font-weight: normal;
  margin: 0 0 1rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #30363d;
}

.placeholder {
  color: #6e7681;
}

.error {
  color: #f85149;
}

.json-output {
  color: #c9d1d9;
}

.json-controls {
  margin-bottom: 1rem;
}

.edit-btn, .save-btn, .reset-btn, .submit-btn {
  padding: 0.25rem 0.75rem;
  background: #21262d;
  color: #c9d1d9;
  border: 1px solid #30363d;
  cursor: pointer;
  font-family: inherit;
  font-size: 0.8rem;
  margin-right: 0.5rem;
  transition: all 0.2s;
}

.edit-btn:hover, .save-btn:hover, .reset-btn:hover, .submit-btn:hover {
  background: #30363d;
  border-color: #58a6ff;
}

.submit-btn {
  background: #238636;
  border-color: #2ea043;
}

.submit-btn:hover {
  background: #2ea043;
  border-color: #46954a;
}

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #238636;
  border-color: #2ea043;
}

.json-editor {
  margin-top: 1rem;
}

.json-textarea {
  width: 100%;
  min-height: 300px;
  padding: 0.75rem;
  background: #0d1117;
  border: 1px solid #30363d;
  color: #c9d1d9;
  font-family: 'Courier New', Monaco, Consolas, monospace;
  font-size: 0.85rem;
  resize: vertical;
  box-sizing: border-box;
}

.json-textarea:focus {
  outline: none;
  border-color: #58a6ff;
}

.editor-actions {
  margin-top: 0.5rem;
}

.json-display {
  background: #0d1117;
  border: 1px solid #30363d;
  padding: 0.75rem;
  margin: 0;
  color: #c9d1d9;
  font-family: 'Courier New', Monaco, Consolas, monospace;
  font-size: 0.85rem;
  overflow-x: auto;
  white-space: pre;
}


.loading-time {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid #30363d;
  color: #6e7681;
  font-size: 0.8rem;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 0.25rem;
}

.loading-time svg {
  opacity: 0.7;
}

.loading-info {
  color: #c9d1d9;
}

.request-info {
  font-family: 'Courier New', Monaco, Consolas, monospace;
}

.endpoint {
  color: #58a6ff;
  font-weight: bold;
  margin-bottom: 1rem;
}

.payload {
  margin-bottom: 1rem;
}

.payload-label {
  color: #f0883e;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.payload-data {
  background: #0d1117;
  border: 1px solid #30363d;
  padding: 0.75rem;
  border-radius: 3px;
  color: #7ee787;
  white-space: pre;
  font-size: 0.85rem;
}


.timer {
  color: #6e7681;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.timer svg {
  opacity: 0.7;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}
</style>
