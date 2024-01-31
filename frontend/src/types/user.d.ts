export interface IUploadRequest {
  file: string, // 文件
}

export interface IUploadResponse {
  url: string, // 素材URL地址
}

export interface IAddressCreateRequest {
  mobile: string, // 手机号码
}

export interface IAddressQueryRequest {
  mobile: string, // 手机号码
}

export interface IAddressUpdateRequest {
  mobile: string, // 手机号码
}

export interface IAddressResponse {
  id: number, // 编号
}

export interface IProfileResponse {
  id: number, // 编号
  name: string, // 名称
}

