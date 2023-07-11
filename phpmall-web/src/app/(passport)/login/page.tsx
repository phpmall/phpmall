'use client'

import type { DatePickerProps } from 'antd';
import { DatePicker } from 'antd';
import { Button } from 'antd'
import styles from './page.module.css'

const onChange: DatePickerProps['onChange'] = (date, dateString) => {
  console.log(date, dateString);
};

export default function Page() {
  return (
    <main>
      <div>
        login page
        <Button type="primary">Button</Button>

        <DatePicker onChange={onChange} />
      </div>
    </main>
  )
}
