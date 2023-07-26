import {captchaApi} from "./api";

export const fetchCaptcha = async () => {
  try {
    return await captchaApi();
  } catch (error) {
    console.error(error);
    throw new Error('Failed to fetch captcha');
  }
};
