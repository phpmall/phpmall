'use client'

import type {ButtonProps, DatePickerProps } from 'antd'
import { Button, DatePicker } from 'antd';
import styles from './page.module.css'


const onChange: DatePickerProps['onChange'] = (date, dateString) => {
  console.log(date, dateString);
};

const onLogin: ButtonProps['onClick'] = (e) => {
  let req = {
    mobile: 'aa',
    password: 'bb',
    captcha: 'cc',
    uuid: 'dd'
  }

  fetch('http://localhost/').then((res) => {
    console.log(req);
  })
}

export default function Page() {
  return (
    <main>
      <div>
        login page
        <hr />
        <DatePicker onChange={onChange} />
        <hr />
        <Button type="primary" onClick={onLogin}>Button</Button>
      </div>
    </main>
  )
}
