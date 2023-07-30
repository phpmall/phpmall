import type {ILoginMobileRequest, ILoginResponse} from "@/services/auth/models";
import {loginApi} from "./api";

export const loginService = async (formData: ILoginMobileRequest): Promise<ILoginResponse> => {
    try {
        return await loginApi(formData);
    } catch (error) {
        console.error(error);
        throw new Error('Failed to fetch users');
    }
};
