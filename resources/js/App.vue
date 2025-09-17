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
      <button @click="processLink" class="action-btn" :disabled="isGenerating">
        {{ isGenerating ? 'Generating...' : 'Generate' }}
      </button>
    </div>
    <div class="preview">
      Preview
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const linkInput = ref('https://www.brompton.com/p/817/c-line-explore-23')
const isGenerating = ref(false)

const processLink = () => {
  if (linkInput.value && !isGenerating.value) {
    isGenerating.value = true
    console.log('Processing:', linkInput.value)

    // Simulate processing time
    setTimeout(() => {
      isGenerating.value = false
    }, 3000)
  }
}
</script>

<style scoped>
:global(*) {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:global(body), :global(html) {
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
  color: #6e7681;
}
</style>
