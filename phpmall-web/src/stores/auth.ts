import { ref } from 'vue'
import { defineStore } from 'pinia'

const tokenKey: string = 'token'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem(tokenKey) || '')

  function isLoggedIn(): boolean {
    return token.value !== ''
  }

  function setJwt(jwt: string) {
    token.value = jwt;
    localStorage.setItem(tokenKey, jwt);
  }

  function clearJwt() {
    token.value = '';
    localStorage.removeItem(tokenKey);
  }

  return { token, isLoggedIn, setJwt, clearJwt }
})
