import { ref } from 'vue'
import { defineStore } from 'pinia'
import { Session } from '@/utils/storage';

const tokenKey: string = 'token'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(Session.get(tokenKey) || '')

  function check(): boolean {
    return token.value !== ''
  }

  function login(jwt: string) {
    token.value = jwt;
    Session.set(tokenKey, jwt);
  }

  function logout() {
    token.value = '';
    Session.clear();
  }

  return { token, check, login, logout }
})
